<?php
if (count(get_included_files()) <= 1) die("Direct access forbidden");
$countries = getCountriesList();
$metaTitle = 'Contact';
include ('layout/header.php');
?>
<section>
    <h1>Contact Us</h1>

    <div class="map">
        <h2>Main Office</h2>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2781.7890741539636!2d15.966758816056517!3d45.795453279106205!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4765d68b5d094979%3A0xda8bfa8459b67560!2sul.+Montevideo+21%2C+10000%2C+Sofia!5e0!3m2!1shr!2shr!4v1509296660756" width="100%" height="400" frameborder="0" allowfullscreen></iframe>
        
    </div>

    <form method="post">

        <h2>Drop us a line</h2>

        <input type="text" name="first_name" id="first_name" placeholder="* First name" />

        <input type="text" name="last_name" id="last_name" placeholder="* Last name" />

        <input type="email" name="email" id="email" placeholder="* E-mail" />

        <select id="country_id" name="country_id" required class="basic-select">
            <option value="" disabled selected hidden>* Country</option>
            <?php
            foreach ($countries as $item) {
                echo sprintf('<option value="%s" data-image="images/flags/%s.png">%s</option>', $item['id'], $item['iso'], $item['country_name']);
            }
            ?>
        </select>

        <textarea name="message" id="message" placeholder="* Message" ></textarea>

        <div class="center"><input type="submit" value="Send" /></div>
    </form>
</section>

<?php
include ('layout/footer.php');
?>