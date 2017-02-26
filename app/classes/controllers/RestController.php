<?php

namespace controllers;

/**
 * Class RestController
 * @package controllers
 */
class RestController
{
    /**
     * RestController constructor.
     */
    public function __construct()
    {
        $this->initHeaders();
    }

    /**
     * Init headers for JSON display
     */
    private function initHeaders()
    {
        header('Content-Type: application/json');
    }

    /**
     * Renders an array as a JSON object
     * @param array $data
     * @return string
     */
    protected function render(array $data)
    {
        return json_encode($data, JSON_NUMERIC_CHECK);
    }
}
