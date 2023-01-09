<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Kernel\Controller\Controller;

class HomeController extends Controller
{
    public function index(Request $request, Response $response)
    {
        return $this->render($response, 'home');
    }
}