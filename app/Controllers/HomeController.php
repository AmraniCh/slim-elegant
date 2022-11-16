<?php

namespace App\Controllers;

use App\kernel\Controller;
use Slim\Http\Request;
use Slim\Http\Response;

class HomeController extends Controller
{
    public function index(Request $request, Response $response)
    {
        return $this->render($response, 'home');
    }
}