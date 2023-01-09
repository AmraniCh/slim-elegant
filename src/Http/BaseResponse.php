<?php

namespace App\Kernel\Http;

use Slim\Http\Stream;
use Slim\Http\Headers;
use Slim\Http\Response;
use Psr\Http\Message\ResponseInterface;

class BaseResponse extends Response
{

    public function __construct($body = null, int $status = 200, array $headers = [])
    {
        $headers = new Headers($headers);

        if ($body) {
            $stream = new Stream(fopen('php://temp', 'r+'));
            $stream->write($body);
            $stream->rewind();
        }

        parent::__construct($status, $headers, $stream ?? $body);
    }

    public static function from(ResponseInterface $response, $body, int $status = 200, array $headers = []): ResponseInterface
    {
        $baseResponse = new static($body, $status, $headers);

        $response->getBody()->write($baseResponse->getBody());

        $response = $response->withStatus($baseResponse->getStatusCode());

        foreach ($baseResponse->getHeaders() as $name => $values) {
            $response = $response->withHeader($name, implode(', ', $values));
        }

        return $response;
    }
}
