<?php declare(strict_types=1);

namespace SharedBookshelf\Controller;

use Doctrine\ORM\EntityRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SharedBookshelf\Auth;
use SharedBookshelf\Configuration;
use SharedBookshelf\Controller\Settings\Collection as ControllerSettings;
use SharedBookshelf\Controller\Settings\Setting;
use SharedBookshelf\Entities\User;
use SharedBookshelf\IpAddress;
use Twig\Environment as Twig;

/**
 * @codingStandardsIgnoreFile
 * @codeCoverageIgnoreStart
 */
class LoginController implements Controller
{
    private Twig $twig;
    private Configuration $config;
    private Auth $auth;

    public function __construct(Twig $twig, Configuration $config, Auth $auth)
    {
        $this->twig = $twig;
        $this->config = $config;
        $this->auth = $auth;
    }

    public function getSettings(): ControllerSettings
    {
        return new ControllerSettings([
            new Setting('/login', 'index', 'get'),
            new Setting('/login/verify', 'verify', 'post'),
            new Setting('/login/exit', 'exit', 'get'),
        ]);
    }

    public function index(Request $request, Response $response, array $args = []): Response
    {
        $template = $this->twig->load('login.twig');
        $data = [
            'debug' => $this->config->isDebug(),
            'loginFailedMessage' => $args['status'] ?? ''
        ];

        $response->getBody()->write(
            $template->render($data)
        );

        return $response;
    }

    public function verify(Request $request, Response $response, array $args = []): Response
    {
        $formData = $request->getParsedBody();
        $username = (string)($formData['username'] ?? '');
        $password = (string)($formData['password'] ?? '');
        $ip = IpAddress::fromRequest($request);

        if ($this->auth->verify($username, $password, $ip)) {
            return $response->withStatus(302)->withHeader('Location', '/admin');
        }

        return $this->index($request, $response, ['status' => 'failed to login']);
    }

    public function exit(Request $request, Response $response, array $args = []): Response
    {
        $this->auth->logout();

        return $this->index($request, $response);
    }
}
