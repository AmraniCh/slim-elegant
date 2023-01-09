<?php

namespace App\Kernel\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class VerifyCrsf
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        if ($request->getAttribute('csrf_status') === false) {
            return new \App\Kernel\Http\PlainResponse('CSRF check fails', 400);
        }
    
        return $next($request, $response);
    }
}