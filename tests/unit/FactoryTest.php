<?php declare(strict_types=1);

namespace SharedBookshelf;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SimpleLog\Logger as SimpleLogger;
use Twig\Environment as Twig;

/**
 * @covers \SharedBookshelf\Factory
 * @uses   \SharedBookshelf\Configuration
 * @uses   \SharedBookshelf\Controller\HomeController
 * @uses   \SharedBookshelf\Controller\Settings\Collection
 * @uses   \SharedBookshelf\Controller\Settings\HttpType
 * @uses   \SharedBookshelf\Controller\Settings\Method
 * @uses   \SharedBookshelf\Controller\Settings\Path
 * @uses   \SharedBookshelf\Controller\Settings\Setting
 * @uses   \SharedBookshelf\Environment
 */
final class FactoryTest extends TestCase
{
    private MockObject|Configuration $configMock;
    private MockObject|SimpleLogger $loggerMock;
    private MockObject|Twig $twigMock;
    private Factory $subject;

    public function setUp(): void
    {
        $this->configMock = $this->createMock(Configuration::class);
        $this->loggerMock = $this->createMock(SimpleLogger::class);
        $this->twigMock = $this->createMock(Twig::class);

        $this->configMock->expects($this->once())->method('getEnvironment')->willReturn(new Environment('unit_test'));

        $this->subject = new FactoryStub(
            $this->configMock,
            $this->loggerMock,
            $this->twigMock
        );
    }

    public function testCanRun(): void
    {
        $this->subject->run();
    }
}
