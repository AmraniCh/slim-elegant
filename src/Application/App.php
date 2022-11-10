<?php

namespace App\Kernal\Application;

use Psr\Container\ContainerInterface;
use Slim\App as SlimApp;

class App extends SlimApp
{
    /** @var string */
    protected $basePath;

    /**
     * @param ContainerInterface|array $container
     * @param string $basePath
     * @throws \RuntimeException
     */
    public function __construct(string $basePath, $container = [])
    {
        if (!$basePath | !is_dir($basePath)) {
            throw new \RuntimeException("The base application path given '$basePath' does not exist.");
        }

        parent::__construct($container);
        $this->basePath = $basePath;
    }

    /**
     * Load environnement variables from the .env file.
     *
     * @param string $envFileDirectory the path of the directory where .env file is located.
     * @return void
     */
    public function loadEnvironnement(string $envFileDirectory = '')
    {
        (\Dotenv\Dotenv::createMutable($envFileDirectory ?: $this->basePath))->load();
    }

    /**
     * Read the configuration files [config/app.php] and merge the app configs with the default 
     * Slim container settings.
     * 
     * @param string $configFile
     * @return void
     */
    public function initConfiguration(string $configFile = '')
    {
        $settings  = $this->getContainer()->get("settings");
        $configs   = require $configFile ?: $this->basePath . '/config/app.php';
        $settings->replace(array_merge($settings->all(), $configs));
    }
}
