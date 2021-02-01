<?php

namespace SharedBookshelf\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SharedBookshelf\Controller\Settings\Collection;

interface Controller
{
    public function index(Request $request, Response $response, array $args = []): Response;
    public function getSettings(): Collection;
}
