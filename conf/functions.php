<?php
if (count(get_included_files()) <= 1) die("Direct access forbidden");

$userLoggedIn = (bool) isset($_SESSION['user']);
if ($userLoggedIn) {
    $role = $_SESSION['user']['role'];
}

// SET ROLE PERMISSIONS – show only those actions and sections which are applicable to the user role
$permissions = array(
    'user'   => array('news' => array('add'),                            'users' => array()),
    'editor' => array('news' => array('index', 'add', 'edit'),           'users' => array()),
    'admin'  => array('news' => array('index', 'add', 'edit', 'delete'), 'users' => array('index', 'edit', 'delete'))
);

// if there was a message saved into the session,
// pass it along to the $message var and delete the session
$message = readFlashMessage();

///////////////////////////////////////////////////////////////////////////////
function getCountriesList() {
    GLOBAL $mysqli;
    $query  = $mysqli->query("SELECT * FROM `countries` ORDER BY `country_name` ASC");
    $result = array();

    while ($row = $query->fetch_assoc()) {
        $result[] = $row;
    }

    return $result;
}

///////////////////////////////////////////////////////////////////////////////
function debug($var = NULL) {
    $backtrace = debug_backtrace();
    $file      = basename($backtrace[0]['file']);
    $line      = $backtrace[0]['line'];

    printf("<strong>%s (%s):</strong><br /><hr />\n", $file, $line);

    echo "<tt>";

    if (is_object($var)) {
        $var = (array) $var;
    }
    if (is_array($var)) {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }
    else {
        echo $var;
    }

    echo "</tt><br /><br />\n";
}

///////////////////////////////////////////////////////////////////////////////
function recursive_html_escape($array, $excludeFields = array('password')) {
    if (!is_array($array)) {
        return $array;
    }

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $array[$key] = recursive_html_escape($value, $excludeFields);
        }
        else {
            $array[$key] = (is_string($value) && !in_array($key, $excludeFields)) ? trim(htmlspecialchars($value)) : $value;
        }
    }

    return $array;
}

///////////////////////////////////////////////////////////////////////////////
// before inserting new data, run mysqli real_escape_string to it
function recursive_mysqli_escape($array) {
    global $mysqli;

    if (!is_array($array)) {
        return $array;
    }

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $array[$key] = recursive_html_escape($value);
        }
        else {
            $array[$key] = (is_string($value) && $key != 'password') ? $mysqli->real_escape_string($value) : $value;
        }
    }

    return $array;
}

///////////////////////////////////////////////////////////////////////////////
function markAsLoggedIn($data, $messageText) {
    // don't store the password in the session
    unset($data['password']);
    $_SESSION['user']    = $data;

    // set welcome message
    setFlashMessage('success', $messageText);

    // redirect to home page 
    header("Location: .");exit;
}

///////////////////////////////////////////////////////////////////////////////
function setFlashMessage($type, $message) {
    $_SESSION['message'] = array(
        'type' => $type,
        'text' => $message,
    );
}

///////////////////////////////////////////////////////////////////////////////
function readFlashMessage() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        return $message;
    }

    return false;

}

///////////////////////////////////////////////////////////////////////////////
function printFlashMessage($type, $text) {
    $class = 'message '.$type;
    $text  = preg_replace('/<br\s?\/?>$/', '', $text); // remove trailing <br /> tag if any
    echo '<div class="'.$class.'">'.$text.'<a href="#" class="close" title="Close"><img src="images/icons/close.png" /></a></div>';
}

///////////////////////////////////////////////////////////////////////////////
function getAllUsers() {
    global $mysqli;
    $result = array();
    $i      = 1;

    // fetch all users and join the countries table to fetch the user's country
    $query  = $mysqli->query("
        SELECT u.*, c.`iso`, c.`country_name`, u2.`username` AS approver
        FROM `users` AS u
            LEFT JOIN `countries` AS c  ON u.`country_id`  = c.`id`
            LEFT JOIN `users`     AS u2 ON u.`approver_id` = u2.`id`
        ORDER BY u.`created` ASC");

    while ($row = $query->fetch_assoc()) {
        $result[$i++] = recursive_html_escape($row);
    }

    return $result;
}

///////////////////////////////////////////////////////////////////////////////
function getUserById($id) {
    global $mysqli;

    if (!$id) { return false; }

    // join tables countries and users to find out country name
    // and username of approver
    $query = $mysqli->query("
        SELECT u.*, c.`iso`, c.`country_name`, u2.`username` AS approver
        FROM `users` AS u
            LEFT JOIN `countries` AS c  ON u.`country_id`  = c.`id`
            LEFT JOIN `users`     AS u2 ON u.`approver_id` = u2.`id`
        WHERE u.`id` = ".intval($id));

    $result = $query->fetch_assoc();

    // convert special characters to html entities
    $result = $result ? recursive_html_escape($result) : false;

    return $result;
}

///////////////////////////////////////////////////////////////////////////////
function getAllNews($onlyActive = false) {
    global $mysqli;
    $result = array();
    $i      = 1;

    $sql = "
        SELECT n.*, u1.`username` AS author, u2.`username` AS approver, GROUP_CONCAT(CONCAT(ni.`id`, ':', ni.`file_name`)) AS images
        FROM `news` AS n
            LEFT JOIN `users`       AS u1 ON n.`author_id`   = u1.`id`
            LEFT JOIN `users`       AS u2 ON n.`approver_id` = u2.`id`
            LEFT JOIN `news_images` AS ni ON n.`id`          = ni.`news_id`";

    if ($onlyActive) {
        $sql .= ' WHERE n.`active` = 1';
    }

    $sql .= " GROUP BY n.`id` ORDER BY n.`created` DESC";

    $query  = $mysqli->query($sql);

    while ($row = $query->fetch_assoc()) {
        $result[$i]           = recursive_html_escape($row);
        $result[$i]['images'] = array_filter(explode(',', $row['images']));
        $i++;
    }

    return $result;
}

///////////////////////////////////////////////////////////////////////////////
function getNewsById($id) {
    global $mysqli;

    if (!$id) { return false; }

    // join tables countries and users to find out country name
    // and username of approver
    $query = $mysqli->query("
        SELECT n.*, u1.`username` AS author, u2.`username` AS approver, GROUP_CONCAT(CONCAT(ni.`id`, ':', ni.`file_name`)) AS images
        FROM `news` AS n
            LEFT JOIN `users`       AS u1 ON n.`author_id`   = u1.`id`
            LEFT JOIN `users`       AS u2 ON n.`approver_id` = u2.`id`
            LEFT JOIN `news_images` AS ni ON n.`id`          = ni.`news_id`
        WHERE n.`id` = ".intval($id));

    $result = $query->fetch_assoc();

    // convert special characters to html entities
    $result = $result ? recursive_html_escape($result, array('description')) : false;

    // convert images data to array
    if ($result) {
        $result['images'] = array_filter(explode(',', $result['images']));
    }

    return $result;
}

///////////////////////////////////////////////////////////////////////////////
// check if there's a record in the `users` table with the same value
function isFieldAvailable($field, $value, $id = NULL) {
    global $mysqli;

    $sql = "SELECT COUNT(*) FROM `users` WHERE `{$field}` = ?";

    // if there's an id specified, *exclude* it from the scanned records
    if (isset($id)) {
        $sql .= " AND `id` != ".intval($id);
    }

    $query = $mysqli->prepare($sql);
    $query->bind_param("s", $value);
    $query->execute();
    $query->bind_result($result);
    $query->fetch();
    $query->close();
    
    return (bool) (!$result);
}

///////////////////////////////////////////////////////////////////////////////
function getUploadErrors($fileArray) {
    $errors = false;

    // all possible upload error codes
    $fileUploadErros = array(
        0 => false, // file was uploaded successfully, errors = false
        1 => 'The size of the file you are trying to upload is too big.',
        2 => 'The size of the file you are trying to upload is too big.',
        3 => 'The uploaded file was not fully uploaded.',
        4 => false, // there was no file to be uploaded, errors = false
        6 => 'Missing a temporary folder.',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
    );

    // if there was no upload error, check the extension
    if ($fileArray['error'] == UPLOAD_ERR_OK && $fileArray['name'] != "") {
        
        // make sure the filename ends on .png, .jpg or .jpeg (case-insensitive)
        if (!preg_match('/\.(png|jpe?g)$/i', $fileArray['name'])) {
            $errors = 'Illegal image extension. Only .jpg, .jpeg and .png allowed.';
        }
    }

    // check if any other problem occured during the upload of the file
    else {
        $errors = $fileUploadErros[ $fileArray['error'] ];
    }

    return $errors;
}

///////////////////////////////////////////////////////////////////////////////
function makeValuesReferenced($arr){
    $refs = array();
    foreach($arr as $key => $value) {
        $refs[$key] = &$arr[$key];
    }
    return $refs;
}