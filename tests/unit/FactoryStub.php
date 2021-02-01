<?php declare(strict_types=1);

namespace SharedBookshelf;

use Twig\Environment as Twig;

class FactoryStub extends Factory
{
    public function __construct(File $configFile, Configuration $configuration, Twig $twig, Framework $framework)
    {
        $this->twig = $twig;
        $this->framework = $framework;
        $this->configuration = $configuration;
        parent::__construct($configFile);
    }
}
