<?php

// Display all errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/autoload.php');
$appConfig = require_once(__DIR__ . '/config.php');

try {
    $router = new Router($appConfig, $_SERVER['REQUEST_URI']);
    $controllerInfo = $router->parse();

    echo '<pre>';
    var_dump($controllerInfo);
    echo '</pre>';
}
catch (\exceptions\RouterException $e) {
    header('Content-Type', 'text/html');
    http_response_code($e->getCode());

    echo $e->getMessage();
}


