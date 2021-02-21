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
use SharedBookshelf\Events\Event;
use SharedBookshelf\Events\Handler\EventHandler;
use Slim\App as Slim;
use Twig\Environment as Twig;

class Framework
{
    private EventStore $eventStore;
    private Twig $twig;
    private Slim $slim;
    private Configuration $config;
    private array $errorController = [];
    private array $eventHandler = [];
    private ?LoggerInterface $logger;

    public function __construct(EventStore $eventStore, Twig $twig, Configuration $config, Slim $slim, ?LoggerInterface $logger = null)
    {
        $this->eventStore = $eventStore;
        $this->twig = $twig;
        $this->config = $config;
        $this->slim = $slim;
        $this->logger = $logger;

        $this->slim->addErrorMiddleware(true, true, true);
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

    public function registerEventHandler(EventHandler $handler): void
    {
        $this->eventHandler[$handler->getType()->asString()] = $handler;
    }

    public function run(): void
    {
        try {
            $this->slim->run();
        } catch (Exception $slimException) {
            $this->renderErrorPage($slimException);
        }
    }

    public function process(): void
    {
        /** @var Event $event */
        $events = $this->eventStore->loadAll();
        foreach ($events as $event) {
            $id = $event->getEventId()->toString();
            $type = $event->getType()->asString();
            echo "Process $id ($type)";
            if (!isset($this->eventHandler[$type])) {
                throw new RuntimeException("Error while processing event '$id' the type '$type' was not registered");
            }
            $handler = $this->eventHandler[$type];
            $handler->handle($event);
            echo PHP_EOL;
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
        if (!$this->config->getEnvironment()->isUnitTest()) {
            $actualException = get_class($exception);
            throw new RuntimeException("Could not find a registered error controller for '$actualException'");
        }
    }
}
// @codeCoverageIgnoreEnd