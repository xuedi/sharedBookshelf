<?php declare(strict_types=1);

namespace SharedBookshelf;

use Slim\App;

class FactoryStub extends Factory
{
    public function __construct(App $appMock)
    {
        $this->slim = $appMock;
        parent::__construct();
    }
}
