<?php
$userLoggedIn = (bool) isset($_SESSION['user']);

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
function recursive_html_escape($array) {
    if (!is_array($array)) {
        return $array;
    }

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $array[$key] = recursive_html_escape($value);
        }
        else {
            $array[$key] = (is_string($value) && $key != 'password') ? trim(htmlspecialchars($value)) : $value; // don't escape passwords!
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
