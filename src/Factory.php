<?php declare(strict_types=1);

namespace SharedBookshelf;

use Awurth\SlimValidation\Validator as FormValidator;
use Awurth\SlimValidation\ValidatorExtension;
use Gregwar\Captcha\CaptchaBuilder;
use Psr\Log\LoggerInterface;
use SharedBookshelf\Controller\Errors\Error404Controller;
use SharedBookshelf\Controller\Errors\ErrorsController;
use SharedBookshelf\Controller\HomeController;
use SharedBookshelf\Controller\ImagesController;
use SharedBookshelf\Controller\LoginController;
use SharedBookshelf\Controller\PrivacyController;
use SharedBookshelf\Controller\SignupController;
use SharedBookshelf\Controller\TermsController;
use SimpleLog\Logger as SimpleLogger;
use Slim\App as Slim;
use Slim\Factory\AppFactory;
use Twig\Environment as Twig;
use Twig\Loader\FilesystemLoader as TwigTemplates;

class Factory
{
    private Twig $twig;
    private Configuration $config;
    private Framework $framework;

    public function __construct(Configuration $config)
    {
        $this->config = $config;
        $this->twig = $this->createTwig();

        $this->framework = $this->createFramework();
        $this->framework->registerController($this->createHomeController());
        $this->framework->registerController($this->createLoginController());
        $this->framework->registerController($this->createSignupController());
        $this->framework->registerController($this->createImagesController());
        $this->framework->registerController($this->createTermsController());
        $this->framework->registerController($this->createPrivacyController());
        $this->framework->registerErrorController($this->createError404Controller());
    }

    public function run(): void
    {
        $this->framework->run();
    }

    // #################### Controller ####################

    private function createHomeController(): HomeController
    {
        return new HomeController($this->twig, $this->config);
    }

    private function createLoginController(): LoginController
    {
        return new LoginController($this->twig, $this->config);
    }

    private function createSignupController(): SignupController
    {
        return new SignupController(
            $this->twig,
            $this->config,
            $this->createCaptchaBuilder(),
            $this->createFormValidator()
        );
    }

    private function createImagesController(): ImagesController
    {
        return new ImagesController($this->twig, $this->config);
    }

    private function createTermsController(): TermsController
    {
        return new TermsController($this->twig, $this->config);
    }

    private function createPrivacyController(): PrivacyController
    {
        return new PrivacyController($this->twig, $this->config);
    }

    private function createError404Controller(): ErrorsController
    {
        return new Error404Controller($this->twig, $this->config);
    }

    // #################### FactoryStub ####################
    // @codeCoverageIgnoreStart

    protected function createTwig(): Twig
    {
        $cache = $this->config->getCachePath();
        if ($this->config->getDebugLevel()) {
            $cache = false;
        }

        return new Twig($this->createTwigTemplates(), [
            'cache' => $cache
        ]);
    }

    private function createTwigTemplates(): TwigTemplates
    {
        return new TwigTemplates($this->config->getTemplatePath());
    }

    protected function createLogger(): LoggerInterface
    {
        return new SimpleLogger($this->config->getErrorLog(), 'error');
    }

    // @codeCoverageIgnoreEnd
    // #################### regular creators ####################

    private function createFramework(): Framework
    {
        return new Framework(
            $this->createTwig(),
            $this->config,
            $this->createSlim(),
            $this->createLogger()
        );
    }

    private function createSlim(): Slim
    {
        return AppFactory::create();
    }

    private function createCaptchaBuilder(): CaptchaBuilder
    {
        return new CaptchaBuilder();
    }

    private function createFormValidator(): FormValidator
    {
        return new FormValidator();
    }
}
