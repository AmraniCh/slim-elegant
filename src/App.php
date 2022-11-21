<?php

namespace App\Kernel;

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
            $container = $this->requireWithVariables($basePath . '/config/container.php');
        } elseif (is_string($container)) {
            if (!file_exists($container)) {
                throw new \RuntimeException("Container settings file '$container' does not exist.");
            }
            $container = $this->requireWithVariables($container);
        } elseif (!$container instanceof ContainerInterface) {
            throw new \RuntimeException("Container parameter type is invalid.");
        }

        parent::__construct($container ?: []);

        $this->basePath = $basePath;
    }

    public function getBasePath(): string
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
     * @param string $configFile
     * @return self
     */
    public function loadConfiguration($configFile = '')
    {
        $configFile = $configFile ?: $this->basePath . '/config/app.php';
        
        if (!file_exists($configFile)) {
            throw new \RuntimeException("Routes file '$configFile' does not exist.");
        }

        $configs = $this->requireWithVariables($configFile);

        $settings = $this->getContainer()->get("settings");
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

        $this->requireWithVariables($routesFile);

        return $this;
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

        $middlewares = $this->requireWithVariables($middlewaresFile);

        foreach ($middlewares as $middleware) {
            $this->add($middleware);
        }

        return $this;
    }

    public function loadEloquent()
    {
        $capsule = new \Illuminate\Database\Capsule\Manager();
        $capsule->addConnection($this->getContainer()->get('settings')['database']);

        $resolver = new \Illuminate\Database\ConnectionResolver(['default' => $capsule->getConnection()]);
        $resolver->setDefaultConnection('default');

        \Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);

        return $this;
    }

    /**
     * @return mixed
     * @throws \LogicException
     */
    private function requireWithVariables($file)
    {
        if (!file_exists($file)) {
            throw new \LogicException("Application file '$file' do not exist.");
        }

        $vars = [
            'app'       => $this,
            'container' => $this->getContainer(),
        ];

        foreach ($vars as $name => $value) {
            ${$name} = $value;
        }

        return require $file;
    }
}
