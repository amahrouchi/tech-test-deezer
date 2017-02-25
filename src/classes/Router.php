<?php

use exceptions\HttpException;

/**
 * Router class
 */
class Router
{
    /**
     * The default http verb to use
     * @var string
     */
    const DEFAULT_VERB = 'GET';

    /**
     * Verb of the current request
     * @var string
     */
    private $verb = self::DEFAULT_VERB;

    /**
     * Current URI to parse
     * @var string
     */
    private $uri;

    /**
     * The application configuration
     * @var array
     */
    private $appConfig;

    /**
     * Router constructor.
     * @param array  $appConfig
     * @param string $uri
     * @param null   $verb
     */
    public function __construct($appConfig, $uri, $verb = null)
    {
        $this->appConfig = $appConfig;
        $this->uri = $uri;

        if (isset($verb))
        {
            $this->verb = $verb;
        }
    }

    /**
     * Parses the current URI to define the controller and action to call
     * @return array
     * @throws HttpException
     */
    public function parse()
    {
        $uriComponents = parse_url($this->uri);
        if (!isset($uriComponents['path']))
        {
            throw HttpException::factory('Unable to parse the route', HttpException::INTERNAL_SERVER_ERROR);
        }

        if (!isset($this->appConfig['routes']))
        {
            throw HttpException::factory('Routes not defined in the app configuration.', HttpException::INTERNAL_SERVER_ERROR);
        }

        foreach ($this->appConfig['routes'] as $route)
        {
            // Validate route params
            if (
                !isset($route['regex'])
                || !isset($route['controller'])
                || !isset($route['action'])
            )
            {
                throw HttpException::factory('Invalid route config', HttpException::INTERNAL_SERVER_ERROR);
            }

            // Default verb
            if (!isset($route['verb']))
            {
                $route['verb'] = self::DEFAULT_VERB;
            }

            if (preg_match($route['regex'], $this->uri, $matches) && $route['verb'] === $this->verb)
            {
                $route['params'] = array_slice($matches, 1);
                return $route;
            }
        }

        throw HttpException::factory('Route not found', HttpException::NOT_FOUND);
    }

}
