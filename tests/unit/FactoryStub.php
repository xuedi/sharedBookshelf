<?php declare(strict_types=1);

namespace SharedBookshelf;

use Slim\App;

class FactoryStub extends Factory
{
    public function __construct(File $configFileMock,App $appMock)
    {
        $this->slim = $appMock;
        $this->configFile = $configFileMock;
        parent::__construct();
    }
}
