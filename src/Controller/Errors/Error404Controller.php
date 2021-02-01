<?php declare(strict_types=1);

namespace SharedBookshelf\Controller\Errors;

use SharedBookshelf\Configuration;
use Slim\Exception\HttpNotFoundException;
use Twig\Environment as Twig;

/**
 * @codingStandardsIgnoreFile
 * @codeCoverageIgnoreStart
 */
class Error404Controller implements ErrorsController
{
    private Twig $twig;
    private Configuration $config;

    public function __construct(Twig $twig, Configuration $config)
    {
        $this->twig = $twig;
        $this->config = $config;
    }

    public function getExceptionClass(): string
    {
        return HttpNotFoundException::class;
    }

    public function execute(): void
    {
        http_response_code(404);
        echo $this->twig->load('404.twig')->render([]);
    }
}
