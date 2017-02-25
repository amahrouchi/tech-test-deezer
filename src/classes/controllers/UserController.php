<?php

namespace controllers;

use exceptions\HttpException;
use models\User;

/**
 * Class UserController
 * @package controllers
 */
class UserController extends RestController
{
    /**
     * View a user's information
     * @param int $userId
     * @return string
     * @throws HttpException
     */
    public function view($userId)
    {
        $user = new User();

        // Unknown user
        if (!$user->load($userId))
        {
            throw HttpException::factory('Unknown user', HttpException::NOT_FOUND);
        }

        // Build response
        $response = [
            'users' => [
                $user->get('user_id') => $user->getAttributes()
            ]
        ];

        return $this->render($response);
    }
}
