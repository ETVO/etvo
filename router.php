<?php

$request = $_SERVER['REQUEST_URI'];
$viewDir = '/';

// Check if the request matches the format '/post/{slug}'
if (preg_match('/^\/post\/([^\/]+)/', $request, $matches)) {
    $slug = $matches[1];
    if (count(explode('/', $request)) > 3) {
        header('Location: /post/' . $slug);
    }
    // Include single.php with the slug as a parameter
    require __DIR__ . $viewDir . 'single.php';
    exit;
}

// Check if the request matches the format '/blog/{slug}'
if (preg_match('/^\/blog\/([^\/]+)/', $request, $matches)) {
    $category_slug = $matches[1];
    if (count(explode('/', $request)) > 3) {
        header('Location: /blog/' . $slug);
    }
    // Include blog.php with the category slug as a parameter
    require __DIR__ . $viewDir . 'blog.php';
    exit;
}


switch ($request) {
    case '':
    case '/':
        require __DIR__ . $viewDir . 'index.php';
        break;

    case '/blog/':
        require __DIR__ . $viewDir . 'blog.php';
        break;

    case '/blog/post/':
        require __DIR__ . $viewDir . 'single.php';
        break;


    default:
        http_response_code(404);
        require __DIR__ . $viewDir . '404.php';
}
