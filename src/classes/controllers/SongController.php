<?php

namespace controllers;

use exceptions\HttpException;
use models\Song;

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
        if (!$song->get($songId))
        {
            throw HttpException::factory('Unknown song', HttpException::NOT_FOUND);
        }

        return $this->render($song->getAttributes());
    }
}
