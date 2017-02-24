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
    'routes' => [
        [
            'regex'      => '#^/user/(\d+)$#',
            'controller' => 'controllers/UserController',
            'action'     => 'view'
        ],
        [
            'regex'      => '#^/user/(\d+)/playlist/(\d+)$#',
            'controller' => 'controllers/PlaylistController',
            'action'     => 'view'
        ]
    ]
];

return $config;
