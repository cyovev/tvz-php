<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="resources/style.css" />
    <script type="text/javascript" src="resources/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="resources/app.js"></script>

    <title>Advanced Web Services Programming (open-source PHP)</title>
    <meta name="description" content="My simple Erasmus+ PHP Project on TVZ" />
    <meta name="keywords" content="PHP,Erasmus,TVZ" />
    
    <meta name="author" content="Christo Yovev" />

</head>
<body>
    <div class="wrapper">
        <header>
            <div class="hero-image"></div>
            <nav>
                <div class="nav-toggler-wrapper">
                    <span id="nav-toggler"></span>
                </div>
                <ul>
                    <?php
                        $nav = array(
                            'home'    => 'Home',
                            'news'    => 'News',
                            'contact' => 'Contact',
                            'about'   => 'About',
                            'gallery' => 'Gallery',
                        );

                        // generate navigation
                        foreach ($nav as $href => $label) {
                            $url = ($href != 'home') ? './?page='.$href : './';
                            echo sprintf('<li><a href="%s">%s</a></li>', $url, $label);
                        }
                    ?>
                </ul>
            </nav>
        </header>
        <main>