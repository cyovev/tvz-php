<?php
if ($_POST) {
    $data     = recursive_html_escape($_POST);
    
    // check if such username exists (and is active)
    $query  = $mysqli->prepare("SELECT * FROM `users` WHERE `active` = 1 AND (`username` = ? OR `email` = ?)");
    $query->bind_param("ss", $data['username'], $data['username']);
    $query->execute();
    $result = $query->get_result();

    // if there's such a row and the password matches,
    // log in the user
    if ($dbRow = $result->fetch_assoc()) {
        if (password_verify($data["password"], $dbRow["password"])) {
            markAsLoggedIn($dbRow, 'Your have successfully logged in!');
        }
    }
    $query->close();

    // otherwise, it's safe to simply declare $message, because if the
    // entered data were correct, the user would have been already redirected
    $message = array(
        'type' => 'errors',
        'text' => 'Incorrect username/password or your account has been deactivated.'
    );
}
include ('layout/header.php');
?>
<section>
    <h1>Log in</h1>

    <form method="post" class="long">

        <input type="text" name="username" id="username" placeholder="Username/E-mail" value="<?= @$data['username']; ?>" required autofocus />

        <input type="password" name="password" id="password" placeholder="Password" required />

        <input type="submit" value="Log In" />
    </form>
</section>

<?php
include ('layout/footer.php');
?>