<?php declare(strict_types=1);

namespace SharedBookshelf\Controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use SharedBookshelf\Configuration;
use Twig\Environment as Twig;

/**
 * @codingStandardsIgnoreFile
 * @codeCoverageIgnoreStart
 */
class HomeController
{
    private Twig $twig;
    private Configuration $config;

    public function __construct(Twig $twig, Configuration $config)
    {
        $this->twig = $twig;
        $this->config = $config;
    }

    public function index(Request $request, Response $response, array $args = []): Response
    {
        $template = $this->twig->load('home.twig');
        $data = [
            'debug' => $this->config->getDebugLevel(),
            'go' => 'here'
        ];

        $response->getBody()->write(
            $template->render($data)
        );

        return $response;
    }
}
