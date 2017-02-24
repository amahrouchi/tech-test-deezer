<?php

use exceptions\RouterException;

/**
 * Router class
 */
class Router
{
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
     * @throws RouterException
     */
    public function parse()
    {
        $uriComponents = parse_url($this->uri);
        if (!isset($uriComponents['path']))
        {
            throw new RouterException('Unable to parse the route', 500);
        }

        if (!isset($this->appConfig['routes']))
        {
            throw new RouterException('Routes not defined in the app configuration.', 500);
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
                throw new RouterException('Invalid route config', 500);
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

        throw new RouterException('Route not found', 404);
    }

}
