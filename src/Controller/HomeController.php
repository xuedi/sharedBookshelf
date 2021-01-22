<?php declare(strict_types=1);

namespace SharedBookshelf\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class HomeController
{
    public function __construct()
    {
        //
    }

    public function index(Request $request, Response $response, array $args = []) {


        $response->getBody()->write('Hello world');

        return $response;
    }
}
