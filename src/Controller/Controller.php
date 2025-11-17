<?php

namespace App\Kernel\Controller;

use Slim\Container;
use Slim\Http\Response;
use App\Kernel\Http\HtmlResponse;
use Slim\Exception\ContainerValueNotFoundException;

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
        try {
            return $this->container->get($key);
        } catch(ContainerValueNotFoundException $ex) {
            throw new ContainerValueNotFoundException("Service '$key' is not defined in the container.");
        }
    }

    /**
     * Render the giving blade template and returns an HTTP response with the compiled template HTML content.
     */
    public function render(Response $response, string $template, array $data = []): Response
    {
        return HtmlResponse::from($response, $this->view->make($template, $data));
    }
}
