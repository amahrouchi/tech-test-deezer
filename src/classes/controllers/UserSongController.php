<?php

namespace controllers;

use exceptions\HttpException;
use models\Song;
use models\User;
use models\UserSong;

/**
 * Class UserSongController
 * @package controllers
 */
class UserSongController extends RestController
{
    /**
     * List a user's favorite songs
     * @param int $userId
     * @param bool $checkUser
     * @return string
     * @throws HttpException
     */
    public function listSongs($userId, $checkUser = true)
    {
        // Check user existence
        $user = new User();
        if (!$user->load($userId) && $checkUser)
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

    /**
     * Add a song to a user favorite songs
     * @param int $userId
     * @param int $songId
     * @return string
     * @throws HttpException
     */
    public function add($userId, $songId)
    {
        // Check user
        $user = new User();
        if (!$user->load($userId))
        {
            throw HttpException::factory('Unknown user', HttpException::NOT_FOUND);
        }

        // Check song
        $song = new Song();
        if (!$song->load($songId))
        {
            throw HttpException::factory('Unknown song', HttpException::NOT_FOUND);
        }

        $userSong = new UserSong();
        $userSong->setAttributes([
            'user_id' => $userId,
            'song_id' => $songId
        ]);

        if ($userSong->insert() === 0)
        {
            throw HttpException::factory("This song is already in the user's favorites", HttpException::BAD_REQUEST);
        }

        return $this->listSongs($userId, false);
    }

    /**
     * Remove a song from the user's favorite
     * @param int $userId
     * @param int $songId
     * @return string
     * @throws HttpException
     */
    public function delete($userId, $songId)
    {
        // Check user
        $user = new User();
        if (!$user->load($userId))
        {
            throw HttpException::factory('Unknown user', HttpException::NOT_FOUND);
        }

        // Check song
        $song = new Song();
        if (!$song->load($songId))
        {
            throw HttpException::factory('Unknown song', HttpException::NOT_FOUND);
        }

        // Check song
        $userSong = new UserSong();
        if (!$userSong->load(['user_id' => $userId, 'song_id' => $songId]))
        {
            throw HttpException::factory("This song does not belong to the user's favorites", HttpException::BAD_REQUEST);
        }

        // Delete favorite
        if($userSong->delete() === 0)
        {
            throw HttpException::factory('Favorite song not found', HttpException::NOT_FOUND);
        }

        return $this->listSongs($userId, false);
    }
}
