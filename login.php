<?php
if (count(get_included_files()) <= 1) die("Direct access forbidden");
if ($userLoggedIn) {
    header("Location: ."); exit;
}
if ($_POST) {
    $data     = recursive_html_escape($_POST);
    
    // check if such username exists (and is active)
    $query  = $mysqli->prepare("SELECT * FROM `users` WHERE (`username` = ? OR `email` = ?)");
    $query->bind_param("ss", $data['username'], $data['username']);
    $query->execute();
    $result = $query->get_result();

    // if there's such a row, it's activated and the passwords match,
    // log in the user
    if ($dbRow = $result->fetch_assoc()) {
        if ($dbRow['active'] && password_verify($data["password"], $dbRow["password"])) {
            markAsLoggedIn($dbRow, 'You have successfully logged in!');
        }
        $query->close();
    }

    // if the active value is 0, the account was not activated
    // if it was though, then there was an error with the username/password
    $errors = (!$dbRow['active']) ? 'Your account has not been activated yet.' : 'Incorrect username/password.';

    // otherwise, it's safe to simply declare $message, because if the
    // entered data were correct, the user would have been already redirected
    $message = array(
        'type' => 'errors',
        'text' => $errors
    );
}
include ('layout/header.php');
?>
<section>
    <h1>Log in</h1>

    <form method="post" class="long">

        <input type="text" name="username" id="username" placeholder="Username/E-mail" value="<?= @$data['username']; ?>" required autofocus />

        <input type="password" name="password" id="password" placeholder="Password" required />

        <div class="center"><input type="submit" value="Log in" /></div>
    </form>
</section>

<?php
include ('layout/footer.php');
?>