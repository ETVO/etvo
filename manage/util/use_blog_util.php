<?php
header('Content-Type: application/json; charset=utf-8');

include dirname(__FILE__) . '/blog_util.php';

$function = $_GET['function'];
$args = isset($_GET['args'])
    ? json_decode($_GET['args'], 1)
    : [];

$return = call_user_func($function, ...$args);

if (is_array($return))
    echo json_encode($return);
else
    echo ($return);
