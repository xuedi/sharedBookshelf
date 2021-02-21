<?php declare(strict_types=1);

namespace SharedBookshelf\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SharedBookshelf\Auth;
use SharedBookshelf\Configuration;
use SharedBookshelf\Controller\Settings\Collection as ControllerSettings;
use SharedBookshelf\Controller\Settings\Setting;
use SharedBookshelf\Entities\UserEntity;
use SharedBookshelf\Repositories\BookRepository;
use SharedBookshelf\Repositories\EventRepository;
use SharedBookshelf\Repositories\UserRepository;
use Twig\Environment as Twig;

/**
 * @codeCoverageIgnore
 */
class AdminController implements Controller
{
    private Twig $twig;
    private Configuration $config;
    private Auth $auth;
    private UserRepository $userRepository;
    private BookRepository $bookRepository;
    private EventRepository $eventRepository;

    public function __construct(
        Twig $twig,
        Configuration $config,
        Auth $auth,
        UserRepository $userRepository,
        BookRepository $bookRepository,
        EventRepository $eventRepository
    ) {
        $this->twig = $twig;
        $this->config = $config;
        $this->userRepository = $userRepository;
        $this->auth = $auth;
        $this->bookRepository = $bookRepository;
        $this->eventRepository = $eventRepository;
    }

    public function getSettings(): ControllerSettings
    {
        return new ControllerSettings([
            new Setting('/admin', 'index', 'get'),
            new Setting('/admin/books', 'books', 'get'),
            new Setting('/admin/users', 'users', 'get'),
            new Setting('/admin/events', 'events', 'get'),
        ]);
    }

    public function index(Request $request, Response $response, array $args = []): Response
    {
        $template = $this->twig->load('admin.twig');
        $response->getBody()->write(
            $template->render([
                'debug' => $this->config->isDebug(),
                'username' => $this->auth->getUsername(),
                'userid' => $this->auth->hasId() ? $this->auth->getId()->toString() : null,
            ])
        );

        return $response;
    }

    public function books(Request $request, Response $response, array $args = []): Response
    {
        $template = $this->twig->load('admin_books.twig');
        $response->getBody()->write(
            $template->render([
                'debug' => $this->config->isDebug(),
                'username' => $this->auth->getUsername(),
                'userid' => $this->auth->hasId() ? $this->auth->getId()->toString() : null,
                'books' => $this->bookRepository->findAll()
            ])
        );

        return $response;
    }

    public function users(Request $request, Response $response, array $args = []): Response
    {
        $template = $this->twig->load('admin_users.twig');
        $response->getBody()->write(
            $template->render([
                'debug' => $this->config->isDebug(),
                'username' => $this->auth->getUsername(),
                'userid' => $this->auth->hasId() ? $this->auth->getId()->toString() : null,
                'users' => $this->userRepository->findAll()
            ])
        );

        return $response;
    }

    public function events(Request $request, Response $response, array $args = []): Response
    {
        $usernameMap = [];
        /** @var UserEntity $user */
        foreach ($this->userRepository->findAll() as $user) { // TODO: move to user repo
            $usernameMap[$user->getId()->toString()] = $user->getUsername();
        }

        $template = $this->twig->load('admin_events.twig');
        $response->getBody()->write(
            $template->render([
                'debug' => $this->config->isDebug(),
                'username' => $this->auth->getUsername(),
                'userid' => $this->auth->hasId() ? $this->auth->getId()->toString() : null,
                'usernameMap' => $usernameMap,
                'events' => $this->eventRepository->findAll()
            ])
        );

        return $response;
    }
}
