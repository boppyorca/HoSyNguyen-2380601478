<?php
// router.php - Router script for PHP Built-in Web Server
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Extract path for routing
$route = ltrim($uri, '/');

// Strip "/webbanhang" prefix if present
if (strpos($route, 'webbanhang/') === 0) {
    $route = substr($route, strlen('webbanhang/'));
} elseif ($route === 'webbanhang') {
    $route = '';
}

// 1. Manually serve static files if they exist in the public directory
$public_file = __DIR__ . '/public/' . $route;
if ($route !== '' && file_exists($public_file) && !is_dir($public_file)) {
    $ext = strtolower(pathinfo($public_file, PATHINFO_EXTENSION));
    $mimes = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon',
        'html' => 'text/html',
        'json' => 'application/json'
    ];
    $mime = $mimes[$ext] ?? 'application/octet-stream';
    header("Content-Type: $mime");
    readfile($public_file);
    exit;
}

// 2. Otherwise, route to index.php
$_GET['url'] = $route;
require_once __DIR__ . '/public/index.php';
