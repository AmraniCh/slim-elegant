<?php

namespace App\Kernel;

use Slim\Container;
use Slim\Http\Response;
use App\Kernel\Http\HtmlResponse;

abstract class Controller
{
    /** @var Container  */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return mixed
     */
    public function __get(string $key)
    {
        if (!$this->container->has($key)) {
            throw new \RuntimeException("Service '$key' is not registered in the container.");
        }

        return $this->container->$key;
    }

    public function render(Response $response, string $template, array $data = []): Response
    {
        return HtmlResponse::from($response, $this->view->make($template, $data));
    }
}
