<?php

namespace App\Kernel;

use Psr\Container\ContainerInterface;
use Slim\App as SlimApp;

class App extends SlimApp
{
    /** @var string */
    private $basePath;

    /** @var array */
    private $appConfiguration;

    /**
     * @param ContainerInterface|array|string $container
     * @throws \RuntimeException
     */
    public function __construct(string $basePath, $container = null)
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
     * Gets application level configuration.
     */
    public function getAppConfiguration(): array
    {
        return $this->appConfiguration;
    }

    /**
     * Load environnement variables from the .env file.
     *
     * @param string $envFileDirectory the path of the directory where .env file is located.
     */
    public function loadEnvironnement(string $envFileDirectory = ''): self
    {
        (\Dotenv\Dotenv::createMutable($envFileDirectory ?: $this->basePath))->load();
        
        return $this;
    }

    /**
     * Read the application configuration file [config/app.php] and merge the app configs with 
     * the default Slim container settings.
     */
    public function loadConfiguration(string $configFile = ''): self
    {
        $configFile = $configFile ?: $this->basePath . '/config/app.php';
        
        if (!file_exists($configFile)) {
            throw new \RuntimeException("Routes file '$configFile' does not exist.");
        }

        $this->appConfiguration = $this->requireWithVariables($configFile);

        $settings = $this->getContainer()->get("settings");
        $settings->replace(array_merge($settings->all(), $this->appConfiguration));

        return $this;
    }

    /**
     * @param string $routesFile
     * @throws \RuntimeException
     */
    public function loadRoutes(string $routesFile = ''): self
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
     */
    public function loadMiddlewares(string $middlewaresFile = ''): self
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

    public function loadEloquent(): self
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
    protected function requireWithVariables(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new \LogicException("Application file '$filePath' do not exist.");
        }

        $vars = [
            'app'       => $this,
            'container' => $this->getContainer(),
        ];

        foreach ($vars as $name => $value) {
            ${$name} = $value;
        }

        return require $filePath;
    }
}
