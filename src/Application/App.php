<?php

namespace App\Kernel\Application;

use Dotenv\Dotenv;
use Slim\App as SlimApp;
use Psr\Container\ContainerInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\ConnectionResolver;
use App\Kernel\Application\ApplicationException;

class App extends SlimApp
{
    /** @var string */
    private $basePath;

    /** @var string */
    private $envDirectory;

    /** @var string */
    private $routesFile;

    /**
     * @param ContainerInterface|array $container
     * 
     * @throws ApplicationException
     */
    public function __construct(string $basePath, $container = [])
    {
        if (!$basePath || !is_dir($basePath)) {
            throw new ApplicationException("Given application base path '$basePath' is invalid or do not exist.");
        }

        if (!$container instanceof ContainerInterface && empty($container)) {
            $container = $this->requireWithVariables("$basePath/config/container.php");
        }

        parent::__construct($container);

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

    public function getRoutesFile(): string
    {
        return $this->routesFile;
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
     * Reads the application configuration file 'config/app.php' and merge 
     * the configuration variables with the default Slim container settings.
     */
    public function loadConfiguration(): self
    {
        $configFile = $this->basePath . '/config/app.php';
        $configVariables = $this->requireWithVariables($configFile);
        $settings = $this->getContainer()->get("settings");
        $settings->replace(array_merge($settings->all(), $configVariables));

        return $this;
    }

    public function loadRoutes(string $routesFile = ''): self
    {
        $routesFile = $routesFile ?: $this->basePath . '/routes.php';
        $this->routesFile = $routesFile;
        $this->requireWithVariables($routesFile);

        return $this;
    }

    public function loadMiddlewares(): self
    {
        $middlewaresFile = $this->basePath . '/config/middlewares.php';
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
     * @throws ApplicationException
     */
    private function requireWithVariables(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new ApplicationException("Application file '$filePath' do not exist.");
        }

        $vars = [
            'app' => $this,
            'container' => $this->getContainer(),
        ];

        foreach ($vars as $name => $value) {
            ${$name} = $value;
        }

        return require $filePath;
    }
}
