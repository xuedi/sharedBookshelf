<?php declare(strict_types=1);

namespace SharedBookshelf\Controller;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use Gregwar\Captcha\CaptchaBuilder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use SharedBookshelf\Configuration;
use SharedBookshelf\Controller\FormValidators\SignupFormValidator;
use SharedBookshelf\Controller\Settings\Collection as ControllerSettings;
use SharedBookshelf\Controller\Settings\Setting;
use SharedBookshelf\Entities\User;
use Twig\Environment as Twig;

/**
 * TODO: write tests for the form handeling
 * @codingStandardsIgnoreFile
 * @codeCoverageIgnoreStart
 * @psalm-suppress UndefinedMagicMethod
 */
class SignupController implements Controller
{
    private Twig $twig;
    private Configuration $config;
    private CaptchaBuilder $captcha;
    private SignupFormValidator $formValidator;
    private EntityRepository $userRepository;

    public function __construct(
        Twig $twig,
        Configuration $config,
        CaptchaBuilder $captcha,
        SignupFormValidator $formValidator,
        EntityRepository $userRepository
    )
    {
        $this->twig = $twig;
        $this->config = $config;
        $this->captcha = $captcha;
        $this->formValidator = $formValidator;
        $this->userRepository = $userRepository;
    }

    public function getSettings(): ControllerSettings
    {
        return new ControllerSettings([
            new Setting('/signup', 'index', 'get'),
            new Setting('/signup/save', 'save', 'post'),
        ]);
    }

    public function index(Request $request, Response $response, array $args = []): Response
    {
        $template = $this->twig->load('signup.twig');
        $data = [
            'debug' => $this->config->isDebug(),
            'captchaImage' => $this->getCaptchaImage(),
        ];

        $response->getBody()->write(
            $template->render($data)
        );

        return $response;
    }

    /**
     * Validator issues
     * @psalm-suppress UndefinedMagicMethod
     * @psalm-suppress MixedMethodCall
     */
    public function save(Request $request, Response $response, array $args = []): Response
    {
        $validatedForm = $this->formValidator->validate($request);

        if ($validatedForm->hasErrors() === false) {
            $this->userRepository->save(new User(
                $validatedForm->getUsername(),
                $validatedForm->getPassword(),
                $validatedForm->getEmail()
            ));
            return $response->withStatus(302)->withHeader('Location', '/profil');
        }

        $template = $this->twig->load('signup.twig');
        $data = [
            'debug' => $this->config->isDebug(),
            'errors' => $validatedForm->getErrors(),
            'captchaImage' => $this->getCaptchaImage(),
            'username' => $validatedForm->getUsername(),
            'password' => $validatedForm->getPassword(),
            'email' => $validatedForm->getEmail(),
        ];

        $response->getBody()->write(
            $template->render($data)
        );

        return $response;
    }

    private function getCaptchaImage(): string
    {
        $this->captcha->setBackgroundColor(220, 220, 220);
        $this->captcha->build(100, 36);
        $_SESSION['captchaCode'] = strtolower((string)$this->captcha->getPhrase());

        return (string)$this->captcha->inline();
    }
}
