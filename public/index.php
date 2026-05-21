<?php
chdir(dirname(__DIR__));
require_once('app/controllers/ProductController.php');
require_once('app/controllers/CategoryController.php');

$request_url = $_SERVER['REQUEST_URI'];
$base_url = '/';

$path = substr($request_url, strlen($base_url));
$path = strtok($path, '?');
$path_parts = array_filter(explode('/', $path));
$path_parts = array_values($path_parts);

$controller = isset($path_parts[0]) ? ucfirst(strtolower($path_parts[0])) : 'Product';
$action = isset($path_parts[1]) ? strtolower($path_parts[1]) : 'index';
$id = isset($path_parts[2]) ? $path_parts[2] : null;

$controller_class = $controller . 'Controller';

if (class_exists($controller_class)) {
    $controller_obj = new $controller_class();

    if (method_exists($controller_obj, $action)) {
        if ($id) {
            $controller_obj->$action($id);
        } else {
            $controller_obj->$action();
        }
    } else {
        echo "Action $action not found";
    }
} else {
    echo "Controller $controller_class not found";
}
