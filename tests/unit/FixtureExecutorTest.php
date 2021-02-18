<?php declare(strict_types=1);

namespace SharedBookshelf;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @covers \SharedBookshelf\FixtureExecutor
 */
final class FixtureExecutorTest extends TestCase
{
    private MockObject|ORMExecutor $ormExecutorMock;
    private MockObject|Loader $loaderMock;
    private FixtureExecutor $subject;

    public function setUp(): void
    {
        $this->ormExecutorMock = $this
            ->getMockBuilder(ORMExecutor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loaderMock = $this
            ->getMockBuilder(Loader::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->subject = new FixtureExecutor(
            $this->ormExecutorMock,
            $this->loaderMock
        );

    }
    public function testCanRetrieveData(): void
    {
        $this->loaderMock->expects($this->once())->method('loadFromDirectory');
        $this->loaderMock->expects($this->once())->method('getFixtures')->willReturn([]);
        $this->ormExecutorMock->expects($this->once())->method('execute');

        $this->subject->execute();
    }
}
