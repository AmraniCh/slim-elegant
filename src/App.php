<?php

namespace App\Kernal;

use Psr\Container\ContainerInterface;
use Slim\App as SlimApp;

class App extends SlimApp
{
    /** @var string */
    protected $basePath;

    /**
     * @param ContainerInterface|array|string $container
     * @param string $basePath
     * @throws \RuntimeException
     */
    public function __construct($basePath, $container = null)
    {
        if (!$basePath | !is_dir($basePath)) {
            throw new \RuntimeException("The base application path given '$basePath' does not exist.");
        }

        if (is_null($container)) {
            $container = require($basePath . '/config/container.php');
        } elseif (is_string($container)) {
            if (!file_exists($container)) {
                throw new \RuntimeException("Container settings file '$container' does not exist.");
            }
            $container = require($container);
        } elseif (!$container instanceof ContainerInterface) {
            throw new \RuntimeException("Container parameter type is invalid.");
        }
        
        parent::__construct($container ?: []);

        $this->basePath = $basePath;
    }

    /**
     * Load environnement variables from the .env file.
     *
     * @param string $envFileDirectory the path of the directory where .env file is located.
     * @return self
     */
    public function loadEnvironnement($envFileDirectory = '')
    {
        (\Dotenv\Dotenv::createMutable($envFileDirectory ?: $this->basePath))->load();
        return $this;
    }

    /**
     * Read the configuration files [config/app.php] and merge the app configs with the default 
     * Slim container settings.
     * 
     * @param string $configFile
     * @return self
     */
    public function loadConfiguration($configFile = '')
    {
        $settings  = $this->getContainer()->get("settings");
        $configs   = require $configFile ?: $this->basePath . '/config/app.php';
        $settings->replace(array_merge($settings->all(), $configs));
        return $this;
    }

    /**
     * @param string $routesFile
     * @throw \RuntimeException
     * @return self
     */
    public function loadRoutes($routesFile = '')
    {
        $routesFile = $routesFile ?: $this->basePath . '/routes.php';
        if (!file_exists($routesFile)) {
            throw new \RuntimeException("Routes file '$routesFile' does not exist.");
        }
        $app = $this;
        require($routesFile);
        return $app;
    }
}
