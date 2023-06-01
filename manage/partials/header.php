<?php
include 'util.php';

$site_title = $settings['site_title'];

if (!isset($active_menu))
    $active_menu = 0;

$menu_options = array(
    'content' => "Content",
    'projects' => "Projects",
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>manage <?php echo $site_title; ?></title>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="font" crossorigin="anonymous" href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap">
        
    <link rel="stylesheet" href="./assets/css/bootstrap.css">
    <link rel="stylesheet" href="./assets/fonts/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="./assets/css/main.css">
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-md">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <span class="etvo">etvo<i>-</i>manage</span> 
                    <span class="site-title"><?php echo $site_title; ?></span> 
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#manageNavbar" aria-controls="manageNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="bi-list"></span>
                </button>
                <div class="collapse navbar-collapse" id="manageNavbar">
                    <ul class="navbar-nav ms-auto mb-2 mb-md-0">
                        <?php foreach ($menu_options as $key => $label) :
                            $href = 'href="' . "$key.php" . '"';

                            $is_current = ($key === $active_menu)
                                ? ' class="nav-link active" aria-current="page"'
                                : ' class="nav-link"';
                        ?>
                            <li class="nav-item" id="<?php echo $key; ?>">
                                <a <?php echo $href . $is_current; ?>><?php echo $label; ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>