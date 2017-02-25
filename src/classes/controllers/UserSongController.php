<?php

namespace controllers;

use exceptions\HttpException;
use models\User;

class UserSongController extends RestController
{
    public function listSongs($userId)
    {
        // Check user existence
        $user = new User();
        if (!$user->load($userId))
        {
            throw HttpException::factory('Unknown user', HttpException::NOT_FOUND);
        }

        // Load songs
        $songs = [];
        $tmpSongs = $user->getSongs();
        foreach ($tmpSongs as $song)
        {
            $songs[$song['song_id']] = $song;
        }

        // Build response
        $response = [
            'users' => [
                $user->get('user_id') => [
                    'songs' => $songs
                ]
            ]
        ];

        return $this->render($response);
    }
}
