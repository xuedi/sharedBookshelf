<?php
// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart

namespace SharedBookshelf;

use Exception;
use Psr\Log\LoggerInterface;
use RuntimeException;
use SharedBookshelf\Controller\Controller;
use SharedBookshelf\Controller\Errors\ErrorsController;
use SharedBookshelf\Controller\Settings\Setting;
use Slim\App as Slim;
use Twig\Environment as Twig;

class Framework
{
    private Twig $twig;
    private Slim $slim;
    private Configuration $config;
    private array $errorController = [];
    private ?LoggerInterface $logger;

    public function __construct(Twig $twig, Configuration $config, Slim $slim, ?LoggerInterface $logger = null)
    {
        $this->twig = $twig;
        $this->config = $config;
        $this->slim = $slim;
        $this->logger = $logger;
    }

    public function registerController(Controller $controller): void
    {
        $settings = $controller->getSettings();
        /** @var Setting $setting */
        foreach ($settings as $setting) {
            $path = $setting->getPath()->asString();
            $method = $setting->getMethod()->asString();
            if ($setting->getType()->isGet()) {
                $this->slim->get($path, [$controller, $method]);
            }
            if ($setting->getType()->isPost()) {
                $this->slim->post($path, [$controller, $method]);
            }
        }
    }

    public function registerErrorController(ErrorsController $controller): void
    {
        $this->errorController[$controller->getExceptionClass()] = $controller;
    }

    public function run(): void
    {
        try {
            $this->slim->run();
        } catch (Exception $slimException) {
            $this->renderErrorPage($slimException);
        }
    }

    private function renderErrorPage(Exception $exception): void
    {
        /** @var ErrorsController $controller */
        foreach ($this->errorController as $controller) {
            $manageableException = $controller->getExceptionClass();
            if ($exception instanceof $manageableException) {
                $controller->execute();
                exit;
            }
        }

        $this->handleException($exception);
    }

    private function handleException(Exception $exception): void
    {
        if ($this->logger) {
            $this->logger->critical('Exception', ['exception' => $exception]);
        }
        if (!$this->config->getEnvironment()->isIsUnitTest()) {
            $actualException = get_class($exception);
            throw new RuntimeException("Could not find a registered error controller for '$actualException'");
        }
    }
}
// @codeCoverageIgnoreEnd