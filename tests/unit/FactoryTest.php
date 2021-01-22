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

    private Factory $subject;

    public function setUp(): void
    {
        $this->appMock = $this->createMock(App::class);
        $this->subject = new FactoryStub($this->appMock);
    }

    public function testCanRun(): void
    {
        $this->appMock->expects($this->once())->method('run');

        $this->subject->run();
    }
}
