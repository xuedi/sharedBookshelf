<?php declare(strict_types=1);

namespace SharedBookshelf;

use Psr\Log\LoggerInterface;
use SimpleLog\Logger as SimpleLogger;
use Twig\Environment as Twig;

class FactoryStub extends Factory
{
    private SimpleLogger $logger;
    private Twig $twig;

    public function __construct(Configuration $config, SimpleLogger $logger, Twig $twig)
    {
        $this->logger = $logger;
        $this->twig = $twig;
        parent::__construct($config);
    }

    protected function createLogger(): LoggerInterface
    {
        return $this->logger;
    }

    protected function createTwig(): Twig
    {
        return $this->twig;
    }
}
