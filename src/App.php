<?php

namespace App\kernel;

use Psr\Container\ContainerInterface;
use Slim\App as SlimApp;

class App extends SlimApp
{
    /** @var string */
    private $basePath;

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
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
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
     * @param string $configsFile
     * @return self
     */
    public function loadConfiguration($configsFile = '')
    {
        $configsFile = $configsFile ?: $this->basePath . '/config/app.php';
        if (!file_exists($configsFile)) {
            throw new \RuntimeException("Routes file '$configsFile' does not exist.");
        }
        $app = $this;
        $configs = require($configsFile);
        $settings = $this->getContainer()->get("settings");
        $settings->replace(array_merge($settings->all(), $configs));
        return $app;
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

    /**
     * @param string $middlewaresFile
     * @throw \RuntimeException
     * @return self
     */
    public function loadMiddlewares($middlewaresFile = '')
    {
        $middlewaresFile = $middlewaresFile ?: $this->basePath . '/config/middleware.php';
        if (!file_exists($middlewaresFile)) {
            throw new \RuntimeException("Middleware file '$middlewaresFile' does not exist.");
        }
        $app = $this;
        $middlewares = require($middlewaresFile);
        foreach ($middlewares as $middleware) {
            $app->add($middleware);
        }
        return $app;
    }

    public function loadEloquent()
    {
        $capsule = new \Illuminate\Database\Capsule\Manager;
        $capsule->addConnection($this->getContainer()->get('settings')['database']);
        $resolver = new \Illuminate\Database\ConnectionResolver(['default' => $capsule->getConnection()]);
        $resolver->setDefaultConnection('default');
        \Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);
        return $this;
    }
}
