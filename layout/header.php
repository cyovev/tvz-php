<?php
if (count(get_included_files()) <= 1) die("Direct access forbidden");
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="images/icons/ubuntu-mate.png" type="image/png" />

    <link rel="stylesheet" href="resources/select2/select2.min.css" />
    <link rel="stylesheet" href="resources/jquery-ui/jquery-ui.css" />
    <link rel="stylesheet" href="resources/style.css" />
    <script type="text/javascript" src="resources/jquery-3.4.1.min.js"></script>
    <?php
    if (isset($userLoggedIn) && $userLoggedIn) {
        echo '<script type="text/javascript" src="resources/tinymce/tinymce.min.js"></script>';
        echo '<script type="text/javascript" src="resources/tinymce/jquery.tinymce.min.js"></script>';
    }
    ?>
    <script type="text/javascript" src="resources/app.js"></script>
    <script type="text/javascript" src="resources/select2/select2.min.js"></script>
    <script type="text/javascript" src="resources/jquery-ui/jquery-ui.js"></script>

    <title><?php if (isset($metaTitle)) { echo $metaTitle." &ndash; "; } ?>Advanced Web Services Programming (open-source PHP)</title>
    <meta name="description" content="My simple Erasmus+ PHP Project on TVZ" />
    <meta name="keywords" content="PHP,Erasmus,TVZ" />
    
    <meta name="author" content="Christo Yovev" />

</head>
<body>
    <div class="wrapper">
        <header>
            <div class="hero-image"></div>
            <nav<?php if (isset($userLoggedIn) && $userLoggedIn) { echo ' class="logged"'; } ?>>
                <div class="nav-toggler-wrapper">
                    <span id="nav-toggler"></span>
                </div>
                <ul>
                    <?php
                        // generate navigation (declared in conf/menu.php)
                        foreach ($nav as $href => $item) {
                            $url   = ($href != 'home') ? './?page='.$href : './';
                            $class = $item['class'] . (($page == $href) ? ' active' : ''); 
                            echo sprintf('<li><a href="%s" class="%s">%s</a></li>', $url, $class, $item['label']);
                        }
                    ?>
                </ul>
            </nav>
        </header>
        <main>
        <?php
        if ($message) {
            printFlashMessage($message['type'], $message['text']);
        }
        ?>