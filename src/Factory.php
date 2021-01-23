<?php declare(strict_types=1);

namespace SharedBookshelf;

use SharedBookshelf\Controller\HomeController;
use Slim\App;

class Factory
{
    private App $slim;
    private Configuration $config;

    public function __construct(App $slim, Configuration $config)
    {
        $this->slim = $slim;
        $this->config = $config;

        $this->setRouting();
    }

    public function run(): void
    {
        $this->slim->run();
    }

    // ### Routing ###

    private function setRouting(): void
    {
        $this->slim->get('/', [$this->createHomeController(), 'index']);
    }

    // ### Controller ###

    private function createHomeController(): HomeController
    {
        return new HomeController();
    }

    // ### Other stuff ###


}
