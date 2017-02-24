<?php

// Display all errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../src/autoload.php');
$appConfig = require_once(__DIR__ . '/../src/config.php');

try
{
    // Routing
    $router         = new Router($appConfig, $_SERVER['REQUEST_URI']);
    $controllerInfo = $router->parse();

    // Controller call
    $controller = new $controllerInfo['controller']();
    $action     = $controllerInfo['action'];
    $params     = $controllerInfo['params'];

    echo call_user_func_array([$controller, $action], $params);
}
catch (\exceptions\RouterException $e)
{
    header('Content-Type', 'text/html');
    http_response_code($e->getCode());

    echo $e->getMessage();
}


