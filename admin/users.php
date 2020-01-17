<?php
if (count(get_included_files()) <= 1) die("Direct access forbidden");

// list all users if the user has permission to see them
// NB! if they don't have the permission, the $action would be set to false anyways
if ($action == 'index') {
    $users = getAllUsers();

    // generate the table
    echo '
        <div class="table-wrapper">
        <table class="admin-table">
            <tr>
                <th>#</th>
                <th>Activated</th>
                <th>Username</th>
                <th>E-mail</th>
                <th>Country</th>
                <th>Register date</th>';

    // if the user has permission to edit other users, show the «Role» column
    if (in_array('edit', $permissions[$role][$section])) {
        echo '<th>Role</th><th>Approved by</th>';
    }

    // if the user has permission to any actions, show the column «Actions»
    if (in_array('edit', $permissions[$role][$section]) || in_array('delete', $permissions[$role][$section])) {
        echo '<th>Actions</th>';
    }

    echo '</tr>';

    // populate the table
    foreach ($users as $i => $item) {
        echo '<tr>
                  <td class="center">' . $i . '</td>
                  <td class="center"><img src="images/icons/' . $activeIcon[ $item['active'] ] . '" /></td>
                  <td>' . $item['username'] . '</td>
                  <td><a href="mailto:' . $item['email'] . '">' . $item['email'] . '</a></td>
                  <td><img src="images/flags/' . $item['iso'] . '.png"> ' . $item['country_name'] . '</td>
                  <td>' . $item['created'] . '</td>';

        // if the user has permission to edit other users, show info about author and approver
        if (in_array('edit', $permissions[$role][$section])) {
            echo '<td>' . ucfirst($item['role']) . '</td>';
            echo '<td>' . ($item['approver'] ? $item['approver'] : '&ndash;') . '</td>';
        }

        // if the user has permission to edit/delete other users,
        // generate respective links
        if (in_array('edit', $permissions[$role][$section]) || in_array('delete', $permissions[$role][$section])) {
            echo '<td class="center">';
            if (in_array('edit',   $permissions[$role][$section])) {
                echo '<a href="index.php?page=admin&section=users&action=edit&id='   . $item['id'] . '" title="Edit"><img src="images/icons/edit.png" /></a>';
            }
            if (in_array('delete', $permissions[$role][$section])) {
                if ($item['id'] != $_SESSION['user']['id']) { echo '<a href="index.php?page=admin&section=users&action=delete&id=' . $item['id'] . '" title="Delete" onclick="javascript: return (confirm(\'Are you sure you want to delete this item?\'));"><img src="images/icons/delete.png" /></a>'; }
                else { echo '<img src="images/icons/delete.png" class="transparent" title="Delete" />'; }
            }
            echo '</td>';
        }
        echo '</tr>';
    }
    echo '</table>
          </div>';
}

// if editing an user
elseif ($action == 'edit') {
    $allRoles = array('user', 'editor', 'admin');
    $id       = (isset($_GET['id'])) ? intval($_GET['id']) : false;
    $user     = getUserById($id);
    $errors   = false;

    if (!$user) {
        printFlashMessage('errors', 'No such user');
    }
    else {
        $countries = getCountriesList();

        // if the form was submitted, try to save the changes
        if ($_POST) {
            // escape all user input to prevent SQL injections on UPDATE
            $data = recursive_mysqli_escape($_POST);

            // check if there's another user with the same username
            if (!isFieldAvailable('username', $data['username'], $id)) {
                $errors .= 'The username <strong>'.$data['username'].'</strong> is already in use. Please pick another one.<br />';
            }

            // check if there's another user with the same email address
            if (!isFieldAvailable('email', $data['email'], $id)) {
                $errors .= 'The email address <strong>'.$data['email'].'</strong> is already in use. Please pick another one.<br />';
            }

            // if the user tries to change their role or deactivate themselves, don't allow it
            if ($user['id'] == $_SESSION['user']['id']) {
                if ($user['role'] != $data['role']) {
                    $errors .= 'You cannot change your own role<br />';
                }
                if (!$data['active']) {
                    $errors .= 'You cannot deactivate yourself.<br />';
                }
            }

            // if there were no errors, proceed with the saving
            if (!$errors) {
                // non-required fields, if empty, should be pased as NULL
                $city       = ($data['city'])       ? "'".$data['city']."'"       : 'NULL';
                $address    = ($data['address'])    ? "'".$data['address']."'"    : 'NULL';
                $birth_date = ($data['birth_date']) ? "'".$data['birth_date']."'" : 'NULL';

                // prepare the query
                $query = "UPDATE `users` SET
                    `active`     = {$data['active']},
                    `role`       = '{$data['role']}',
                    `first_name` = '{$data['first_name']}',
                    `last_name`  = '{$data['last_name']}',
                    `username`   = '{$data['username']}',
                    `email`      = '{$data['email']}',
                    `country_id` = '{$data['country_id']}',
                    `city`       =  {$city},
                    `address`    =  {$address},
                    `birth_date` =  {$birth_date}";

                // if the user was inactive so far, and now they get activated
                // use the current user's ID as approver_id
                if (!$user['active'] && $data['active']) {
                    $data['approver_id'] = $_POST['approver_id'] = $_SESSION['user']['id'];
                    $_POST['approver']   = $_SESSION['user']['username'];
                    $query              .= ", `approver_id` = ".intval($data['approver_id']);
                }

                // if the password field was filled in,
                // hash it and store it
                if ($data['password']) {
                    $data['password'] = $_POST['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                    $query           .= ", `password` = '".$data['password']."'";
                }

                // add the ID of the user to the query
                // (to prevent all other users from being updated :))
                $query .= " WHERE `id` = ".$id;

                // run the query
                $mysqli->query($query);

                // store any potential mysql errors returned from the server
                $errors = $mysqli->error;

            }

            // when displaying data, use the one that the user entered last
            $user = recursive_html_escape($_POST);

            // if any errors occured during the saving, display them
            if ($errors) {
                printFlashMessage('errors', $errors);
            }

            // otherwise display the success message
            else {
                printFlashMessage('success', 'Data successfully updated!');
            }
        }

        echo '<form method="post" class="admin-form">
                  <h2>Edit user</h2>
                  <div class="row">
                      <label for="active0">Activated</label>
                      <span class="field">
                          <input type="radio" name="active" value="1" id="active1"'.($user['active']  ? ' checked="checked"' : '').' />
                          <label class="radio" for="active1"><img src="images/icons/' . $activeIcon[1] . '" /> Active</label>'.(($user['active'] && isset($user['approver_id']) && isset($user['approver'])) ? ' (<em>Approved by <a href="index.php?page=admin&section=users&action=edit&id='.$user['approver_id'].'">'. @$user['approver']. '</a></em>)' : '') . '<br /><br />
                          <input type="radio" name="active" value="0" id="active0"'.(!$user['active'] ? ' checked="checked"' : '').' />
                          <label class="radio" for="active0"><img src="images/icons/' . $activeIcon[0] . '" /> Inactive</label>
                      </span>
                  </div>

                  <div class="row">
                      <label>Role</label>
                      <div class="field"><select id="role" name="role" class="basic-select">';

                      foreach ($allRoles as $item) {
                          $selected = ($item == $user['role']) ? ' selected="selected"' : '';
                          echo sprintf('<option value="%s" %s>%s</option>', $item, $selected, ucfirst($item))."\n";
                      }

        echo '
                      </select></div>
                  </div>

                  <div class="row">
                      <label for="first_name">First name</label>
                      <div class="field"><input type="text" name="first_name" id="first_name" value="'. $user['first_name'] .'" required /></div>
                  </div>

                  <div class="row">
                      <label for="last_name">Last name</label>
                      <div class="field"><input type="text" name="last_name" id="last_name" value="'. $user['last_name']. '" required /></div>
                  </div>

                  <div class="row">
                      <label for="username">Username</label>
                      <div class="field"><input type="text" name="username" id="username" value="'. $user['username']. '" pattern="[a-z0-9]{3,}" placeholder="Only alphanumeric values allowed, no spaces" required /></div>
                  </div>


                  <div class="row">
                      <label for="email">E-mail</label>
                      <div class="field"><input type="email" name="email" id="email" value="'. $user['email']. '" required /></div>
                  </div>

                  <div class="row">
                      <label for="password">Password</label>
                      <div class="field"><input type="password" name="password" placeholder="To keep current password, leave this field empty" id="password" pattern="[^\s]{8,}" /></div>
                  </div>

                  <div class="row">
                      <label>Country</label>
                      <div class="field"><select id="country_id" name="country_id" class="basic-select">';

                      foreach ($countries as $item) {
                          $selected = ($item['id'] == $user['country_id']) ? ' selected="selected"' : '';
                          echo sprintf('<option value="%s" data-image="images/flags/%s.png"%s>%s</option>', $item['id'], $item['iso'], $selected, $item['country_name'])."\n";
                      }

        echo '
                      </select></div>
                  </div>

                  <div class="row">
                      <label for="city">City</label>
                      <div class="field"><input type="text" name="city" id="city" value="'.@$user['city'].'" /></div>
                  </div>
                      
                  <div class="row">
                      <label for="address">Address</label>
                      <div class="field"><input type="text" name="address" id="address" value="'.@$user['address'].'" /></div>
                  </div>

                  <div class="row">
                      <label for="birth_date">Date of birth</label>
                      <div class="field"><input type="text" name="birth_date" id="birth_date" class="datepicker" pattern="[0-9]{4}-(0[1-9]|1[012])-[0-9]{2}" value="'. @$user['birth_date']. '" /></div>
                  </div>

                  <center><input type="submit" value="Save changes" /></center>
              </form>';
    }

}

// deleting users
elseif ($action == 'delete') {
    $id     = (isset($_GET['id'])) ? intval($_GET['id']) : false;
    $errors = false;
    
    // check if such a user exists in the first place
    $user   = getUserById($id);

    if (!$user) {
        $errors = 'No such user';
    }

    else {
        // if the user tries to delete themselves, don't allow it
        if ($_SESSION['user']['id'] == $id) {
            $errors = 'You cannot delete yourself';
        }

        // if there were no errors, proceed with the deletion
        if (!$errors) {
            $mysqli->query("DELETE FROM `users` WHERE `id` = ".$id);

            // store any potential mysql errors returned from the server
            $errors = $mysqli->error;

            // if there were affected rows (should be only 1), show success message
            if ($mysqli->affected_rows) {
                $flashMsg = 'User <strong>'.htmlspecialchars($user['username']).'</strong> was successfully deleted!';
            }
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
    if (in_array('index', $permissions[$role][$section])) {
        $flashMsg .= '<br />Go back to <a href="index.php?page=admin&section='.$section.'&action=index">all users</a>.';
    }

    printFlashMessage($msgType, $flashMsg);
}
?>