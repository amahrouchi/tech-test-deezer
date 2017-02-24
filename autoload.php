<?php

require_once(__DIR__ . '/classes/Autoload.php');

$autoload = new Autoload(__DIR__);
spl_autoload_register([$autoload, 'run']);
