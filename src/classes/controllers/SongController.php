<?php

namespace controllers;

use exceptions\HttpException;
use models\Song;

/**
 * Class SongController
 * @package controllers
 */
class SongController extends RestController
{
    /**
     * View a song information
     * @param int $songId
     * @return string
     * @throws HttpException
     */
    public function view($songId)
    {
        $song = new Song();

        // Unknown song
        if (!$song->load($songId))
        {
            throw HttpException::factory('Unknown song', HttpException::NOT_FOUND);
        }

        // Build response
        $response = [
            'songs' => [
                $song->get('song_id') => $song->getAttributes()
            ]
        ];

        return $this->render($response);
    }
}
