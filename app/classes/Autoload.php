<?php

/**
 * Handles classes autoloading
 */
class Autoload
{
    /**
     * The application root folder
     * @var string
     */
    private $appRoot;

    /**
     * Autoload constructor.
     * @param string $appRoot Our app web root
     */
    public function __construct($appRoot)
    {
        $this->appRoot = $appRoot;
    }

    /**
     * Runs the autoload for the specified class name
     * @param string $classname
     */
    public function run($classname)
    {
        $path = str_replace('\\', '/', $classname);

        $classPath = $this->appRoot . '/classes/' . $path . '.php';
        if (!file_exists($classPath))
        {
            throw new \RuntimeException("Unable to find $classPath");
        }

        require_once($classPath);
    }
}
