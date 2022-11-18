<?php

namespace App\Kernel\Http;

use Slim\Http\Stream;
use Slim\Http\Headers;
use Slim\Http\Response;
use Psr\Http\Message\ResponseInterface;

class BaseResponse extends Response
{

    public function __construct(string $body = null, int $status = 200, array $headers = [])
    {
        $headers = new Headers($headers);

        $newStream = new Stream(fopen('php://temp', 'r+'));
        $newStream->write($body);
        $newStream->rewind();

        parent::__construct($status, $headers, $newStream);
    }

    public static function from(ResponseInterface $response, $body, $status = 200, $headers = [])
    {
        $static = new static($body, $status, $headers);
        $response->getBody()->write($static->getBody());
        $response = $response->withStatus($static->getStatusCode());
        foreach ($static->getHeaders() as $name => $values) {
            $response = $response->withHeader($name, implode(', ', $values));
        }
        return $response;
    }
}
