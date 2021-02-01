<?php declare(strict_types=1);

namespace SharedBookshelf;

use Psr\Log\LoggerInterface;
use SharedBookshelf\Controller\Errors\Error404Controller;
use SharedBookshelf\Controller\Errors\ErrorsController;
use SharedBookshelf\Controller\HomeController;
use SharedBookshelf\Controller\LoginController;
use SharedBookshelf\Controller\SignupController;
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
        $this->framework->registerErrorController($this->createError404Controller());
    }

    public function run(): void
    {
        $this->framework->run();
    }

    // ### Controller ###

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
        return new SignupController($this->twig, $this->config);
    }

    private function createError404Controller(): ErrorsController
    {
        return new Error404Controller($this->twig, $this->config);
    }

    // ### Other stuff ###

    private function createFramework(): Framework
    {
        return new Framework(
            $this->createTwig(),
            $this->config,
            $this->createSlim(),
            $this->createLogger()
        );
    }

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

    private function createSlim(): Slim
    {
        return AppFactory::create();
    }

    protected function createLogger(): LoggerInterface
    {
        return new SimpleLogger($this->config->getErrorLog(), 'error');
    }
}
