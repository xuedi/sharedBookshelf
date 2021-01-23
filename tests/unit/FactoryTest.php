<?php declare(strict_types=1);

namespace SharedBookshelf;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Slim\App;

/**
 * @covers \SharedBookshelf\Factory
 * @uses   \SharedBookshelf\Configuration
 * @uses   \SharedBookshelf\Controller\HomeController
 */
final class FactoryTest extends TestCase
{
    /** @var MockObject|App */
    private $appMock;

    /** @var MockObject|Configuration */
    private $configMock;

    private Factory $subject;

    public function setUp(): void
    {
        $this->configMock = $this->createMock(Configuration::class);
        $this->appMock = $this->createMock(App::class);
        $this->subject = new Factory(
            $this->appMock,
            $this->configMock
        );
    }

    public function testCanRun(): void
    {
        $this->appMock->expects($this->once())->method('run');

        $this->subject->run();
    }
}
