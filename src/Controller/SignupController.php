<?php declare(strict_types=1);

namespace SharedBookshelf\Controller;

use Awurth\SlimValidation\Validator as FormValidator;
use Gregwar\Captcha\CaptchaBuilder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as V;
use SharedBookshelf\Configuration;
use SharedBookshelf\Controller\Settings\Collection as ControllerSettings;
use SharedBookshelf\Controller\Settings\Setting;
use Twig\Environment as Twig;

/**
 * @codingStandardsIgnoreFile
 * @codeCoverageIgnoreStart
 * @psalm-suppress UndefinedMagicMethod
 */
class SignupController implements Controller
{
    private Twig $twig;
    private Configuration $config;
    private CaptchaBuilder $captcha;
    private FormValidator $formValidator;

    public function __construct(Twig $twig, Configuration $config, CaptchaBuilder $captcha, FormValidator $formValidator)
    {
        $this->twig = $twig;
        $this->config = $config;
        $this->captcha = $captcha;
        $this->formValidator = $formValidator;
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
            'debug' => $this->config->getDebugLevel(),
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
        $formData = $request->getParsedBody();

        // set filter rules
        $this->formValidator->validate($request, [
            'username' => V::length(4, 32)->alnum('_')->noWhitespace(),
            'password' => V::length(4, 32)->alnum('_'),
            'email' => V::notBlank()->email(),
        ]);

        // check form validation and captcha code
        $formErrors = $this->formValidator->getErrors();
        $captchaSession = (string)($_SESSION['captchaCode'] ?? '');
        $captchaForm = strtolower((string)($formData['captchaCode'] ?? 'RandomNotMatching'));
        if ($captchaForm != $captchaSession) {
            $formErrors['captchaCode'] = 'The capcha does not match';
        }

        // success, no errors found
        if (empty($formErrors)) {
            return $response->withStatus(302)->withHeader('Location', '/profil');
        }

        $template = $this->twig->load('signup.twig');
        $data = [
            'debug' => $this->config->getDebugLevel(),
            'errors' => $formErrors,
            'captchaImage' => $this->getCaptchaImage(),
            'username' => $formData['username'] ?? '',
            'password' => $formData['password'] ?? '',
            'email' => $formData['email'] ?? '',
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
