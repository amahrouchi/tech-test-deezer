<?php

/*
 * Routes :
 * GET /user/id
 * GET /song/id
 * GET /user/id/songs
 * PUT /user/id/songs/id
 * DELETE /user/id/songs/id
 */

// Display all errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/autoload.php');

$test = new \models\User();
var_dump($test);

