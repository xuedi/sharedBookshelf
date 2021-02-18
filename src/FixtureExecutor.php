<?php declare(strict_types=1);

namespace SharedBookshelf;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;

class FixtureExecutor
{
    private Loader $loader;
    private ORMExecutor $ormExecutor;

    public function __construct(ORMExecutor $ormExecutor, Loader $loader)
    {
        $this->loader = $loader;
        $this->ormExecutor = $ormExecutor;
    }

    public function execute(): void
    {
        $this->loader->loadFromDirectory(__DIR__ . '/Fixtures');
        $this->ormExecutor->execute($this->loader->getFixtures());
    }
}
