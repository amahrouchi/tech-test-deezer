<?php

/*
 * Routes :
 * GET /user/id
 * GET /song/id
 * GET /user/id/songs
 * PUT /user/id/songs/id
 * DELETE /user/id/songs/id
 */

$config = [
    'database' => [
        'dsn' => 'mysql:host=localhost;dbname=deezer;charset=utf8mb4',
        'user' => 'deezer',
        'password' => 'deezer'
    ],

    'routes' => [
        [
            'regex'      => '#^/user/(\d+)$#',
            'verb'       => 'GET',
            'controller' => '\controllers\UserController',
            'action'     => 'view'
        ],
    ]
];

return $config;
