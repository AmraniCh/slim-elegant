<?php

namespace App\Kernel\Controller;

use Slim\Container;
use Slim\Http\Response;
use App\Kernel\Http\HtmlResponse;

abstract class Controller
{
    /** @var Container  */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @return mixed
     */
    public function __get(string $key)
    {
        if (!$this->container->has($key)) {
            throw new \LogicException("Service '$key' is not registered with the container.");
        }

        return $this->container->get($key);
    }

    public function render(Response $response, string $template, array $data = []): Response
    {
        return HtmlResponse::from($response, $this->view->make($template, $data));
    }
}
