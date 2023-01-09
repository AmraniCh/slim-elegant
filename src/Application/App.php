<?php

namespace App\Kernel\Application;

use Dotenv\Dotenv;
use Slim\App as SlimApp;
use Psr\Container\ContainerInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\ConnectionResolver;
use App\Kernel\Application\Exception\ApplicationFileException;

class App extends SlimApp
{
    /** @var string */
    private $basePath;

    /** @var string */
    private $envDirectory;

    /** @var string */
    private $appConfigFile;

    /** @var string */
    private $routesFile;

    /** @var string */
    private $middlewaresFile;

    /**
     * @param ContainerInterface|array|string $container
     * 
     * @throws ApplicationFileException|\InvalidArgumentException
     */
    public function __construct(string $basePath, $container = null)
    {
        if (!$basePath | !is_dir($basePath)) {
            throw new ApplicationFileException("The base application path given '$basePath' does not exist.");
        }

        if (is_null($container)) {
            $container = $this->requireWithVariables($basePath . '/config/container.php');
        } elseif (is_string($container)) {
            if (!file_exists($container)) {
                throw new ApplicationFileException("Container settings file '$container' does not exist.");
            }
            $container = $this->requireWithVariables($container);
        } elseif (!$container instanceof ContainerInterface) {
            throw new \InvalidArgumentException("Container parameter type is invalid.");
        }

        parent::__construct($container ?: []);

        $this->basePath = $basePath;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function getEnvDirectory(): string
    {
        return $this->envDirectory;
    }

    public function getAppConfigFile(): string
    {
        return $this->appConfigFile;
    }

    public function getRoutesFile(): string
    {
        return $this->routesFile;
    }

    public function getMiddlewaresFile(): string
    {
        return $this->middlewaresFile;
    }

    /**
     * Load environment variables from the .env file.
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
     * Read the application configuration file [config/app.php] and merge the app configs with 
     * the default Slim container settings.
     * 
     * @throws ApplicationFileException
     */
    public function loadConfiguration(): self
    {
        $configFile = $this->basePath . '/config/app.php';
        
        if (!file_exists($configFile)) {
            throw new ApplicationFileException("Application configuration file '$configFile' does not exist.");
        }

        $this->appConfigFile = $configFile;

        $appConfigs = $this->requireWithVariables($configFile);
        $settings = $this->getContainer()->get("settings");
        $settings->replace(array_merge($settings->all(), $appConfigs));

        return $this;
    }

    /**
     * @param string $routesFile
     * 
     * @throws ApplicationFileException
     */
    public function loadRoutes(string $routesFile = ''): self
    {
        $routesFile = $routesFile ?: $this->basePath . '/routes.php';

        if (!file_exists($routesFile)) {
            throw new ApplicationFileException("Routes file '$routesFile' does not exist.");
        }

        $this->routesFile = $routesFile;

        $this->requireWithVariables($routesFile);

        return $this;
    }

    /**
     * @param string $middlewaresFile
     * 
     * @throws ApplicationFileException
     */
    public function loadMiddlewares(): self
    {
        $middlewaresFile = $this->basePath . '/config/middleware.php';

        if (!file_exists($middlewaresFile)) {
            throw new ApplicationFileException("Middlewares file '$middlewaresFile' does not exist.");
        }

        $this->middlewaresFile = $middlewaresFile;

        $middlewares = $this->requireWithVariables($middlewaresFile);

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

    /**
     * @return mixed
     * 
     * @throws ApplicationFileException
     */
    private function requireWithVariables(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new ApplicationFileException("Application file '$filePath' do not exist.");
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
