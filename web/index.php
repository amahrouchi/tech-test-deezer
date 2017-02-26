<?php

// Display all errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../src/autoload.php');
$appConfig = require_once(__DIR__ . '/../src/config.php');

try
{
    // Database
    $dsn = $appConfig['database']['dsn'];
    $user = $appConfig['database']['user'];
    $password = $appConfig['database']['password'];
    $pdo = new PDO($dsn, $user, $password);
    \models\ActiveRecord::setPDO($pdo);

    // Routing
    $router         = new Router($appConfig, $_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    $controllerInfo = $router->parse();

    // Controller call
    $controller = new $controllerInfo['controller']();
    $action     = $controllerInfo['action'];
    $params     = $controllerInfo['params'];

    echo call_user_func_array([$controller, $action], $params);
}
catch (\exceptions\HttpException $e)
{
    header('Content-Type: application/json');
    http_response_code($e->getCode());
    echo $e->getMessage();
}
catch (\Exception $e)
{
    header('Content-Type: text/html');
    http_response_code(500);
    echo get_class($e) . ': ' . $e->getMessage();
}
