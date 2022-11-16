<?php

namespace App\kernel;

use Slim\Http\Response;
use App\kernel\Http\HtmlResponse;

class Controller
{
    protected $container;

    public function __construct(\Slim\Container $container)
    {
        $this->container = $container;
    }

    public function __get($name)
    {
        if (!$this->container->has($name)) {
            throw new \RuntimeException("The service '$name' is not registered in the container.");
        }

        return $this->container->$name;
    }

    public function render(Response $response, string $template, array $data = []): Response
    {  
        return HtmlResponse::from($response, $this->view->make($template, $data));
    }
}