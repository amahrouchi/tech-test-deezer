<?php

class Autoload
{
    private $webRoot;

    /**
     * Autoload constructor.
     * @param string $webRoot Our app web root
     */
    public function __construct($webRoot)
    {
        $this->webRoot = $webRoot;
    }

    /**
     * Runs the autoload for the specified class name
     * @param string $classname
     */
    public function run($classname)
    {
        $path = str_replace('\\', '/', $classname);

        $classPath = $this->webRoot . '/classes/' . $path . '.php';
        if (!file_exists($classPath))
        {
            throw new \RuntimeException("Unable to find $classPath");
        }

        require_once($classPath);
    }
}
