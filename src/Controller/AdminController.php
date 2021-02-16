<?php declare(strict_types=1);

namespace SharedBookshelf\Controller;

use Doctrine\ORM\EntityRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SharedBookshelf\Configuration;
use SharedBookshelf\Controller\Settings\Collection as ControllerSettings;
use SharedBookshelf\Controller\Settings\Setting;
use SharedBookshelf\Repositories\UserRepository;
use Twig\Environment as Twig;

/**
 * @codeCoverageIgnore
 */
class AdminController implements Controller
{
    private Twig $twig;
    private Configuration $config;
    private EntityRepository|UserRepository $userRepository;

    public function __construct(Twig $twig, Configuration $config, EntityRepository $userRepository)
    {
        $this->twig = $twig;
        $this->config = $config;
        $this->userRepository = $userRepository;
    }

    public function getSettings(): ControllerSettings
    {
        return new ControllerSettings([
            new Setting('/admin', 'index', 'get'),
        ]);
    }

    public function index(Request $request, Response $response, array $args = []): Response
    {
        $users = $this->userRepository->findAll();

        $template = $this->twig->load('admin.twig');
        $response->getBody()->write(
            $template->render(['users' => $users])
        );

        return $response;
    }
}
