<?php
include 'user_util.php';
include 'util.php';

$site_title = $settings['site_title'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login to manage <?php echo $site_title; ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link as="style" rel="stylesheet preload" crossorigin="anonymous" href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap">

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">

    <link rel="stylesheet" href="./assets/css/bootstrap.css">
    <link rel="stylesheet" href="./assets/fonts/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="./assets/css/main.css">
</head>

<body>

<div class="login container">
    <div class="heading">
        <h1>
        Login
        </h1> 
    </div>
    
    <form action="" method="post">
        <div class="mb-3">
            <input type="text" name="username" id="username" class="form-control" placeholder="Username">
        </div>
        <div class="mb-3">
            <input type="text" name="password" id="password" class="form-control" placeholder="Password">
        </div>
    </form>
</div>

<footer>
    <?php echo date('Y'); ?> &copy; ETVO
</footer>

</body>
<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

<script src="./assets/js/blocks.js" defer></script>

<script src="./assets/js/main.js" defer></script>


</html>