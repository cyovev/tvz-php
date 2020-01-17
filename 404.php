<?php
if (count(get_included_files()) <= 1) die("Direct access forbidden");
$metaTitle = 'Error 404';
include ('layout/header.php');
?>

<article>
    <div class="error-404">404</div>
    <div class="error-404-text">
        The page cannot be found.<br />
        Go to <a href="./">home page.</a>
    </div>
</article>

<?php
include ('layout/footer.php');
?>