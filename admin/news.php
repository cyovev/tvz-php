<?php
if (count(get_included_files()) <= 1) die("Direct access forbidden");

$canListNews   = (bool) in_array('index',  $permissions[$role][$section]);
$canAddNews    = (bool) in_array('add',    $permissions[$role][$section]);
$canEditNews   = (bool) in_array('edit',   $permissions[$role][$section]);
$canDeleteNews = (bool) in_array('delete', $permissions[$role][$section]);

$canEditUsers  = (bool) in_array('edit',   $permissions[$role]['users']);

// the deletion of thumbs is an action on its own, but it actually belongs to the edit action
// therefore, once done, simulate going to the edit action
if (isset($_GET['action']) && ($_GET['action'] == 'delete_thumb') && $canEditNews) {
    $id      = (isset($_GET['id'])) ? intval($_GET['id']) : false;
    $news_id = intval($_GET['news_id']);

    // check if such an image exists
    $query  = $mysqli->query("SELECT * FROM `news_images` WHERE `news_id` = ".$news_id." AND `id` = ".$id);

    // if so, delete its record and the file
    if (!($row = $query->fetch_assoc())) {
        printFlashMessage('errors', 'No such image');
    }
    else {
        $mysqli->query("DELETE FROM `news_images` WHERE `id` = ".$id);

        $itemFolder = 'images/news/uploads/'.$news_id;
        @unlink($itemFolder.'/'.$row['file_name']);

        // check how many files are left in the folder (after the deletion)
        // if there are none, simply delete the folder as well
        $filesInFolder = array_slice(scandir($itemFolder), 2); // slicing 2 because of folders . and ..
        if (!$filesInFolder) {
            @rmdir($itemFolder);
        }
        
        printFlashMessage('success', 'Image deleted successfully!');
    }

    // faux set action and news id (because redirect is not possible at this point)
    $_GET['id'] = $news_id;
    $action     = 'edit';
}

// if the user has permission to add new articles,
// generate a link to do so
if ($canAddNews && (!$action || ($action == 'index'))) {
    echo '<div class="admin-actions">
              <a href="index.php?page=admin&section='.$section.'&action=add"><img src="images/icons/add.png" width="16" />Add new</a>
          </div>';
}

// list news if the user has permission to see them
if ($action == 'index') {
    $news = getAllNews();

    // generate the table
    echo '
        <div class="table-wrapper">
        <table class="admin-table">
            <tr>
                <th>#</th>
                <th>Visible</th>
                <th>Image</th>
                <th>Title</th>
                <th>Published</th>';

    // if the user has permission to edit other users, show the «Author» and «Approved By» columns 
    if ($canEditUsers) {
        echo '<th>Author</th><th>Approved By</th>';
    }

    // if the user has permission to any actions, show the column «Actions»
    if ($canEditNews || $canDeleteNews) {
        echo '<th>Actions</th>';
    }

    echo '</tr>';

    // populate the table
    foreach ($news as $i => $item) {
        if ($item['images']) {
            list($imageId, $imageName) = explode(':', $item['images'][0]);
        }
        $img = ($item['images']) ? '<a href="images/news/uploads/'.$item['id'].'/'.$imageName.'" target="_blank" title="'.$imageName.'"><img src="images/news/uploads/'.$item['id'].'/'.$imageName.'" /></a>' : '';
        echo '<tr>
                  <td class="center">'.$i++.'</td>
                  <td class="center"><img src="images/icons/' . $activeIcon[ $item['active'] ] . '" /></td>
                  <td class="center cover">'.$img.'</td>
                  <td><div class="title" title="' . $item['title'] . '">' . $item['title'] . '</div></td>
                  <td class="center">' . $item['created'] . '</td>';

        // if the user has permission to edit other users, show info about author and approver (if any)
        if ($canEditUsers) {
            echo '<td><a href="index.php?page=admin&section=users&action=edit&id='.$item['author_id'].'" title="Open profile">'.$item['author'].'</a></td>';

            if ($item['approver'] && $item['active']) {
                echo '<td><a href="index.php?page=admin&section=users&action=edit&id='.$item['approver_id'].'" title="Open profile">'.$item['approver'].'</a></td>';
            }
            else{
                echo '<td>&ndash;</td>';
            }
        }

        // if the user has permission to edit/delete news articles,
        // generate respective links
        if ($canEditNews || $canDeleteNews) {
            echo '<td class="center actions">';
            if ($canEditNews)   { echo '<a href="index.php?page=admin&section=news&action=edit&id='  .$item['id'].'" title="Edit"><img src="images/icons/edit.png" /></a>'; }
            if ($canDeleteNews) { echo '<a href="index.php?page=admin&section=news&action=delete&id='.$item['id'].'" title="Delete" onclick="javascript: return (confirm(\'Are you sure you want to delete this item?\'));"><img src="images/icons/delete.png" /></a>'; }
            echo '</td>';
        }
        echo '</tr>';
    }
    echo '</table>
          </div>';
}

// if adding or editing a news article
elseif ($action == 'edit' || $action == 'add') {
    $errors = $news_id = false;
    $news   = array();

    // if the user is trying to edit an article, fetch it from the DB
    if ($action == 'edit') {
        $id      = (isset($_GET['id'])) ? intval($_GET['id']) : false;
        $news    = getNewsById($id);
        $news_id = $news ? $news['id'] : false;
    }

    if ($action == 'edit' && !$news) {
        printFlashMessage('errors', 'No such news article');
    }

    // if news article exists OR the action is «add»
    else {
        // if the form was submitted, try to save the changes
        if ($_POST) {
            $data     = $_POST;
            $fileName = $_FILES['images']['name'];
            $tmpName  = $_FILES['images']['tmp_name'];
            $errors   = getUploadErrors($_FILES['images']);

            // if there were no errors with the image, proceed with the saving of the article
            if (!$errors) {

                // stores the parameters used for the query (they can be dynamic)
                $params = array("isss", $data['active'], $data['title'], $data['summary'], $data['description']);

                // if the action is 'add', the query is a simple INSERT
                if ($action == 'add') {
                    $params[0] .= 'i';
                    $params[]   = $_SESSION['user']['id'];
                    $statement  = "INSERT INTO `news` (`active`, `title`, `summary`, `description`, `author_id`, `approver_id`) VALUES(?, ?, ?, ?, ?, NULL)";
                }

                // otherwise prepare an UPDATE query
                elseif ($action == 'edit') {
                    $statement = "UPDATE `news` SET `active` = ?, `title` = ?, `summary` = ?, `description` = ?";

                    // if the user was inactive so far, and now they get activated
                    // use the current user's ID as approver_id
                    if (!$news['active'] && $data['active']) {
                        $data['approver_id'] = $_SESSION['user']['id'];
                        $data['approver']    = $_SESSION['user']['username'];

                        // add approver id to the query
                        $statement          .= ", `approver_id` = ?";

                        // add type of value and value to the parameters
                        $params[0]          .= 'i';
                        $params[]            = intval($data['approver_id']);
                    }

                    // add the ID of the user to the query
                    $statement .= " WHERE `id` = ".$id;
                }


                // prepare the query
                $query  = $mysqli->prepare($statement);

                // pass the array parameters as single arguments
                $ref    = new ReflectionClass('mysqli_stmt');
                $method = $ref->getMethod("bind_param");
                $method->invokeArgs($query, makeValuesReferenced($params));

                // execute and close the query
                $query->execute();
                $query->close();

                // store any potential mysql errors returned from the server
                $errors = $mysqli->error;

                // insert the image to table `news_images`
                if (!$errors) {
                    $news_id = ($action == 'edit') ? $news['id'] : $mysqli->insert_id;
                    if ($fileName && $tmpName) {
                        $folder   = 'images/news/uploads/'.$news_id;

                        // create the directory if it doesn't exist yet
                        if (!file_exists($folder)) {
                            if (!mkdir($folder, 0755, true)) {
                                $errors .= 'Cannot create folder';
                            }
                        }

                        // if the creation of the directory was successful, 
                        // copy the uploaded file to the new directory and then create a record in `news_images`
                        if (!$errors) {
                            if (!move_uploaded_file($tmpName, $folder.'/'.$fileName)) {
                                $errors .= 'Cannot upload file '.$fileName;
                            }
                            else {
                                $mysqli->query("INSERT INTO `news_images` (`news_id`, `file_name`) VALUES({$news_id}, '{$mysqli->real_escape_string($fileName)}')");

                                if ($mysqli->affected_rows) {
                                    $news['images'][] = $mysqli->insert_id.':'.$fileName;
                                }
                            }
                        }
                    }

                }

            }

            // when displaying data, use the one that the user entered last
            $news = array_merge($news, recursive_html_escape($data, array('description')));

            // if any errors occured during the saving, show them
            if ($errors) {
                printFlashMessage('errors', $errors);
            }

            // otherwise display the success message
            else {
                if ($action == 'edit') {
                    $text = 'Data successfully updated!';
                }
                else {
                    $text = 'Article successfully added!';

                    if (!$canEditNews) {
                        $text .= '<br />Please wait for an approval.';
                    }

                }
                printFlashMessage('success', $text);
            }

        }

        // if the action is add, and the user has successfully added the article
        if ($action == 'add' && $_POST && !$errors) {

            // if the user has no permission to edit news, simply show them "Add another article?" link
            if (!$canEditNews) {
                echo '<p>Add <a href="index.php?page=admin&section='.$section.'&action='.$action.'">another article</a>?</p>';
            }

            // if they do have permission to edit, load the data as if it is being edited from now on
            // by faux setting action and news id (because redirect is not possible at this point)
            else {
                $news_id = $mysqli->insert_id;
                $news    = getNewsById($news_id);
                $action  = 'edit';
            }
        }

        // show the form only if the action is add AND there were errors or no $_POST info
        // OR if it the action is edit and the user has proper permissions to edit
        if ((($action == 'add' && (!$_POST || $errors)) || ($action == 'edit' && $canEditNews))) {
            echo '<form method="post" action="index.php?page=admin&section='.$section.'&action='.$action.($news_id ? '&id='.$news_id : '').'" class="admin-form" enctype="multipart/form-data">
                      <h2>'.ucfirst($action).' article'.((@$news['active'] && @$news_id) ? '<a href="index.php?page=news&id='.$news_id.'" target="_blank" class="place-right" title="Preview"><img src="images/icons/preview.png" /></a>' : '').'</h2>';

                if ($canEditNews) {
                    echo '
                      <div class="row">
                          <label for="active">Visible</label>
                          <span class="field">
                              <input type="hidden" name="active" value="0" />
                              <input type="checkbox" name="active" id="active" value="1"'.(@$news['active']  ? ' checked="checked"' : '').' />'.((@$news['active'] && @$news['approver_id'] && @$news['approver']) ? ' (<em>Approved by <a href="index.php?page=admin&section=users&action=edit&id='.@$news['approver_id'].'">'. @$news['approver']. '</a></em>)' : '').'
                          </span>
                      </div>';
                }
                else {
                    echo '<input type="hidden" name="active" value="0" />';
                }

                if ($canEditNews && @$news['author']) {
                    echo '<div class="row">
                              <label>Author</label>
                              <div class="field"><strong>'. @$news['author'] .'</strong></div>
                          </div>';
                }


                echo '<div class="row">
                          <label for="title">Title</label>
                          <div class="field"><input type="text" name="title" id="title" value="'. @$news['title'] .'" required /></div>
                      </div>

                      <div class="row">
                          <label for="summary">Summary</label>
                          <div class="field"><textarea name="summary" rows="8" id="summary" required>'. @$news['summary']. '</textarea></div>
                      </div>

                      <div class="row">
                          <label for="summary">Text</label>
                          <div class="field"><textarea name="description" rows="8" class="tinymce" id="description">'. @$news['description']. '</textarea></div>
                      </div>

                      <div class="row">
                          <label for="images">
                            Images<br />
                            <em class="small">Recommended size: 310x250px</em></label>
                          <div class="field">';

                      if (@$news['images']) {
                          foreach (@$news['images'] as $i => $item) {
                              list($imageId, $imageName) = explode(':', $item);
                              if (!$i) { echo '<div class="thumbs">'; }
                              $path = $news_id.'/'.$imageName;
                              echo '<a href="images/news/uploads/'.$path.'" target="_blank"><img src="images/news/uploads/'.$path.'" /></a><a href="index.php?page=admin&section=news&action=delete_thumb&news_id='.$news_id.'&id='.$imageId.'" title="Delete" onclick="javascript: return (confirm(\'Are you sure you want to delete this item?\'));"><img src="images/icons/delete.png" /></a>';
                              if (!isset($news['images'][$i+1])) { echo '</div>'; }

                          }
                      }

                      echo '<input type="file" id="images" name="images" accept=".jpg,.jpeg,.png" />
                          </div>
                      </div>

                      <center><input type="submit" value="Save changes" /></center>
                  </form>';
        }
    }
}

elseif ($action == 'delete') {
    $id     = (isset($_GET['id'])) ? intval($_GET['id']) : false;
    $errors = false;
    
    // check if such a user exists in the first place
    $news   = getNewsById($id);

    if (!$news) {
        $errors = 'No such article';
    }

    else {

        $mysqli->query("DELETE FROM `news` WHERE `id` = ".$id);

        // store any potential mysql errors returned from the server
        $errors = $mysqli->error;

        // if there were affected rows (should be only 1), show success message
        if ($mysqli->affected_rows) {
            $flashMsg = 'News article <strong>'.htmlspecialchars($news['title']).'</strong> was successfully deleted!';

            // delete also uploads from the folder
            foreach ($news['images'] as $item) {
                @unlink('images/news/uploads/'.$news['id'].'/'.$item);
            }
            @rmdir('images/news/uploads/'.$news['id']);
        }
    }

    // if there were any errors so far, pass them to $flashMsg and display them
    if ($errors) {
        $msgType  = 'errors';
        $flashMsg = $errors;
    }
    else {
        $msgType  = 'success';
    }

    // if the user has permissions to see the users list,
    // generate a link to get them there
    if ($canListNews) {
        $flashMsg .= '<br />Go back to <a href="index.php?page=admin&section='.$section.'&action=index">all news</a>.';
    }

    printFlashMessage($msgType, $flashMsg);
}
?>