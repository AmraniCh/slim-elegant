<?php

if (!function_exists('app')) {
    /**
     * Allows accessing the app instance as a function.
     */
    function app(): \App\Kernel\Application\App
    {
        global $app;
        return $app;
    }
}

if (!function_exists('container')) {
    function container(): Slim\Container
    {
        return app()->getContainer();
    }
}

if (!function_exists('_env')) {
    /**
     * @return mixed
     * 
     * @throws \LogicException
     */
    function _env(string $key)
    {
        if (!array_key_exists($key, $_ENV)) {
            throw new \LogicException("'$key' does not exist with environment variables.");
        }

        return $_ENV[$key];
    }
}

if (!function_exists('config')) {
    /**
     * Get configuration values.
     * 
     * @return mixed
     * 
     * @throws \LogicException
     */
    function config(string $key)
    {
        global $app;

        $config = require $app->getBasePath() . '/config/app.php';

        // support accessing other configuration files values:
        // if the requested config var is not exist in the main configuration file (app.php)
        // then search in the config directory for a configuration file with the same name 
        // as the config name and return its content
        if (!array_key_exists($key, $config)) {
            $configFile = sprintf("%s/config/%s.php", $app->getBasePath(), $key);
            if (!file_exists($configFile)) {
                throw new \LogicException("'$key' configuration variable not exist.");
            }
            $value = $app->getFileLoader()->loadConfiguration($configFile, $app->getGlobals());
        } else {
            $value = $config[$key];
        }

        if ($value instanceof \Closure) {
            return call_user_func($value);
        }

        if ($value === 'true') {
            return true;
        }
        
        if ($value === 'false') {
            return false;
        }

        return $value;
    }
}

if (!function_exists('route')) {
    function route(string $routeName, array $data = []): string
    {
        global $app;
        
        return $app
            ->getContainer()
            ->get('router')
            ->pathFor($routeName, $data);
    }
}

if (!function_exists('session')) {
    function session(): SlimSession\Helper
    {
        return container()->get('session');
    }
}