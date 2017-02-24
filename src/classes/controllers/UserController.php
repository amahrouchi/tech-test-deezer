<?php

namespace controllers;

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
     */
    public function view($userId)
    {
        return $this->render([
            'user_id' => $userId
        ]);
    }
}
