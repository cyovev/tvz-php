<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/*                                FUNCTIONS                                    */
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
///////////////////////////////////////////////////////////////////////////////
function createRegistration(&$data) {
    global $mysqli;
    $errors = checkEmailUniqueness($data['email']);
    $status = 0;
    if (!$errors) {
        $data['active']   = 1; // mark the user as activated straight away
        $data['username'] = generateUsername($data['first_name'], $data['last_name']);
        $password         = password_hash($data['password'], PASSWORD_DEFAULT);
        $query            = $mysqli->prepare("INSERT INTO `users` (`active`, `first_name`, `last_name`, `email`, `country_id`, `city`, `address`, `birth_date`, `username`, `password`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
        $query->bind_param("ssssssssss", $data['active'], $data['first_name'], $data['last_name'], $data['email'], $data['country_id'], $data['city'], $data['address'], $data['birth_date'], $data['username'], $password);
        $query->execute();
        $query->close();

        $status = 1;
    }

    return array($status, $errors);
}

///////////////////////////////////////////////////////////////////////////////
// check if there's a record in the `users` table with the same email address
function checkEmailUniqueness($email) {
    global $mysqli;

    $query = $mysqli->prepare("SELECT COUNT(*) FROM `users` WHERE `email` = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $query->bind_result($result);
    $query->fetch();
    $query->close();
    
    return ($result) ? 'The email address <strong>'.$email.'</strong> is already in use.<br /> Please choose a different one.' : false;
}

///////////////////////////////////////////////////////////////////////////////
function generateUsername($fName, $lName) {
    global $mysqli;

    $fName    = simplify($fName);
    $lName    = simplify($lName);

    // take first letter of first name, and add last name
    $username = $root = substr($fName, 0, 1).$lName;

    // check if such username already exists
    // if so, start adding 1, 2, 3... at the end until uniquness is reached
    $query    = $mysqli->prepare("SELECT COUNT(*) FROM `users` WHERE `username` = ?");
    $i        = 0;

    do {
        $query->bind_param("s", $username);
        $query->execute();
        $query->bind_result($result);
        $query->fetch();

        if ($result) {
            $username = $root.++$i;
        }
    }
    while($result);
    $query->close();

    return $username;
}

///////////////////////////////////////////////////////////////////////////////
// replace all kinky croatian characters,
// remove all non-letter chars and convert to lowercase
function simplify($string) {
    $croatian = array('Č', 'č', 'Ć', 'ć', 'Š', 'š', 'Ž', 'ž', 'Đ', 'đ');
    $latin    = array('C', 'c', 'C', 'c', 'S', 's', 'Z', 'z', 'Dj', 'd');
    $string   = strtolower(preg_replace('/[^a-zA-Z]/i', '', str_replace($croatian, $latin, $string)));

    return $string;
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/*                              END OF FUNCTIONS                               */
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
$countries = getCountriesList();
if ($_POST) {
    $data                   = recursive_html_escape($_POST);
    list ($status, $errors) = createRegistration($data);

    // if the status is true, the registration was successful:
    // log in the user, set success message session
    // and redirect to home page
    if ($status) {
        markAsLoggedIn($data, 'Thank you for registering! Your username is: '.$data['username']);
    }

    // otherwise display the error message
    else {
        $message = array(
            'type' => 'errors',
            'text' => $errors,
        );
    }
}
include ('layout/header.php');
?>
<section>
    <h1>Registration</h1>

    <form method="post" class="long">

        <h2>Please enter your data</h2>

        <input type="text" name="first_name" id="first_name" placeholder="* First name" value="<?= @$data['first_name']; ?>" required />

        <input type="text" name="last_name" id="last_name" placeholder="* Last name" value="<?= @$data['last_name']; ?>" required />

        <input type="email" name="email" id="email" placeholder="* E-mail" value="<?= @$data['email']; ?>" required <?php if (isset($status) && !$status) { echo "autofocus"; }?> />

        <input type="password" name="password" id="password" placeholder="* Password (at least 8 characters)" required pattern="[^\s]{8,}" />

        <select id="country_id" name="country_id" required class="basic-select">
            <option value="" disabled<?php if (!@$data['country_id']) { echo " selected"; } ?> hidden>* Country</option>
            <?php
            foreach ($countries as $item) {
                $selected = ($item['id'] == @$data['country_id']) ? ' selected="selected"' : '';
                echo sprintf('<option value="%s" data-image="images/flags/%s.png"%s>%s</option>', $item['id'], $item['iso'], $selected, $item['country_name'])."\n";
            }
            ?>
        </select>

        <input type="text" name="city" id="city" placeholder="City" value="<?= @$data['city']; ?>"  />

        <input type="text" name="address" id="address" placeholder="Address" value="<?= @$data['address']; ?>"  />

        <input type="text" name="birth_date" id="birth_date" class="datepicker" placeholder="Birth Date (YYYY-MM-DD)" pattern="[0-9]{4}-(0[1-9]|1[012])-[0-9]{2}" value="<?= @$data['birth_date']; ?>"  />

        <input type="submit" value="Register" />
    </form>
</section>

<?php
include ('layout/footer.php');
?>