<?php declare(strict_types=1);

namespace SharedBookshelf;

use SharedBookshelf\Controller\HomeController;
use Slim\App;
use Twig\Environment as Twig;
use Twig\Loader\FilesystemLoader as TwigTemplates;

class Factory
{
    private App $slim;
    private Twig $twig;
    private Configuration $config;

    public function __construct(App $slim, Configuration $config)
    {
        $this->slim = $slim;
        $this->config = $config;
        $this->twig = $this->createTwig();

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
        return new HomeController($this->twig, $this->config);
    }

    // ### Other stuff ###

    private function createTwig(): Twig
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
}
