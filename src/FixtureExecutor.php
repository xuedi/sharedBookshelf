<?php declare(strict_types=1);

namespace SharedBookshelf;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;

class FixtureExecutor
{
    private EntityManager $entityManager;
    private Loader $loader;

    public function __construct(EntityManager $entityManager, Loader $loader)
    {
        $this->entityManager = $entityManager;
        $this->loader = $loader;
    }

    public function execute()
    {
        $this->loader->loadFromDirectory(__DIR__ . '/Fixtures');

        $purger = new ORMPurger();

        $executor = new ORMExecutor($this->entityManager, $purger);
        $executor->execute($this->loader->getFixtures());
    }
}
