<?php declare(strict_types=1);

namespace SharedBookshelf\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SharedBookshelf\Configuration;
use SharedBookshelf\Controller\Settings\Collection as ControllerSettings;
use SharedBookshelf\Controller\Settings\Setting;
use Twig\Environment as Twig;

class ImagesController implements Controller
{
    private Twig $twig;
    private Configuration $config;

    public function __construct(Twig $twig, Configuration $config)
    {
        $this->twig = $twig;
        $this->config = $config;
    }

    public function getSettings(): ControllerSettings
    {
        return new ControllerSettings([
            new Setting('/images', 'index', 'get'),
        ]);
    }

    public function index(Request $request, Response $response, array $args = []): Response
    {
        $template = $this->twig->load('images.twig');
        $response->getBody()->write(
            $template->render([])
        );

        return $response;
    }
}
