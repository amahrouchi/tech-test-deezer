<?php

/*
 * Routes :
 * GET /user/id
 * GET /song/id
 * GET /user/id/songs
 * POST /user/id/songs
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
            'regex'      => '#^/users/(\d+)$#',
            'verb'       => 'GET',
            'controller' => '\controllers\UserController',
            'action'     => 'view'
        ],

        [
            'regex'      => '#^/songs/(\d+)$#',
            'verb'       => 'GET',
            'controller' => '\controllers\SongController',
            'action'     => 'view'
        ],

        [
            'regex'      => '#^/users/(\d+)/songs$#',
            'verb'       => 'GET',
            'controller' => '\controllers\UserSongController',
            'action'     => 'listSongs'
        ],

        [
            'regex'      => '#^/users/(\d+)/songs/(\d+)$#',
            'verb'       => 'POST',
            'controller' => '\controllers\UserSongController',
            'action'     => 'add'
        ],

        [
            'regex'      => '#^/users/(\d+)/songs/(\d+)$#',
            'verb'       => 'DELETE',
            'controller' => '\controllers\UserSongController',
            'action'     => 'delete'
        ],
    ]
];

return $config;
