<?php declare(strict_types=1);

namespace SharedBookshelf;

use SharedBookshelf\Controller\HomeController;
use Slim\App;
use Slim\Factory\AppFactory;

class Factory
{
    protected ?App $slim = null;
    private Configuration $config;

    public function __construct()
    {
        $this->config = $this->createConfiguration();
        $this->slim ??= AppFactory::create();

        $this->setRouting();
    }

    public function run()
    {
        $this->slim->run();
    }

    // ### Routing ###

    private function setRouting()
    {
        $this->slim->get('/', [$this->createHomeController(), 'index']);
    }

    // ### Controller ###

    private function createHomeController(): HomeController
    {
        return new HomeController();
    }

    // ### Stuff ###

    private function createConfiguration(): Configuration
    {
        return new Configuration();
    }
}
