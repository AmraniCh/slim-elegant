<?php

namespace App\Kernel\Application;

use Dotenv\Dotenv;
use Slim\App as SlimApp;
use App\Kernel\FileLoader\FileLoader;
use Psr\Container\ContainerInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\ConnectionResolver;

class App extends SlimApp
{
    /** @var string */
    private $basePath;

    /** @var FileLoader */
    private $fileLoader;

    /** @var array */
    private $globals = [];

    /** @var string */
    private $envDirectory;

    /** @var string */
    private $routesFile;

    /**
     * @param ContainerInterface|array $container
     * 
     * @throws \LogicException
     */
    public function __construct(string $basePath, $container = [], ?FileLoader $fileLoader = null)
    {
        if (!$basePath || !is_dir($basePath)) {
            throw new \LogicException("The given application base path '$basePath' is invalid or do not exist.");
        }

        $this->fileLoader = $fileLoader ?: new FileLoader;

        // set up global variables that should be available in every configuration file 
        $this->globals = [
            'app'       => $this,
            'container' => $this->getContainer(),
        ];

        if (!$container instanceof ContainerInterface && empty($container)) {
            $container = $this->fileLoader->loadConfiguration("$basePath/config/container.php", $this->globals);
        }

        parent::__construct($container);

        $this->basePath = $basePath;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function getFileLoader(): FileLoader
    {
        return $this->fileLoader;
    }

    public function getGlobals(): array
    {
        return $this->globals;
    }

    public function getEnvDirectory(): string
    {
        return $this->envDirectory;
    }

    public function getRoutesFile(): string
    {
        return $this->routesFile;
    }

    /**
     * Allows you to add new global variables to be used in the configuration files. 
     */
    public function addGlobals(array $variables): self
    {
        foreach ($variables as $name => $value) {
            if (!array_key_exists($name, $this->globals)) {
                $this->globals[$name] = $value;
            }
        }

        return $this;
    }

    /**
     * Loads environment variables from the .env file.
     *
     * @param string $envDirectory the path of the directory where .env file is located.
     */
    public function loadEnvironment(string $envDirectory = ''): self
    {
        $this->envDirectory = $envDirectory ?: $this->basePath;
        $dotenv = Dotenv::createMutable($this->envDirectory);
        $dotenv->load();
        
        return $this;
    }

    /**
     * Loads the application configurations from the 'config/app.php' file
     * and merge the configs with the Slim container settings so all 
     * configuration values can be accessed via the container.
     */
    public function loadConfiguration(): self
    {
        $configFile = $this->basePath . '/config/app.php';
        $configVariables = $this->fileLoader->loadConfiguration($configFile, $this->globals);
        $settings = $this->getContainer()->get("settings");
        $settings->replace(array_merge($settings->all(), $configVariables));

        return $this;
    }

    public function loadRoutes(string $routesFile = ''): self
    {
        $routesFile = $routesFile ?: $this->basePath . '/routes.php';
        $this->routesFile = $routesFile;
        $this->fileLoader->load($routesFile, $this->globals);

        return $this;
    }

    public function loadMiddlewares(): self
    {
        $middlewaresFile = $this->basePath . '/config/middlewares.php';
        $middlewares = $this->fileLoader->loadConfiguration($middlewaresFile, $this->globals);
        foreach ($middlewares as $middleware) {
            $this->add($middleware);
        }

        return $this;
    }

    public function loadEloquent(): self
    {
        $capsule = new Manager;
        $capsule->addConnection($this->getContainer()->get('settings')['database']);
        $resolver = new ConnectionResolver(['default' => $capsule->getConnection()]);
        $resolver->setDefaultConnection('default');
        Model::setConnectionResolver($resolver);

        return $this;
    }
}
