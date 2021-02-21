<?php declare(strict_types=1);

namespace SharedBookshelf\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SharedBookshelf\Entities\EventEntity;
use SharedBookshelf\Entities\UserEntity;
use SharedBookshelf\Events\DummyEvent;
use SharedBookshelf\Repositories\UserRepository;

/**
 * @covers \SharedBookshelf\Repositories\EventRepository
 * @uses   \SharedBookshelf\Entities\EventEntity
 * @uses   \SharedBookshelf\EventType
 * @uses   \SharedBookshelf\Events\DummyEvent
 */
final class EventRepositoryTest extends TestCase
{
    private EventRepository $subject;
    private MockObject|EntityManagerInterface $entityManagerMock;
    private MockObject|ClassMetadata $classMetadata;
    private MockObject|Query $queryMock;

    public function setUp(): void
    {
        $this->queryMock = $this
            ->getMockBuilder(Query::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->entityManagerMock = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->classMetadata = $this
            ->getMockBuilder(ClassMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->subject = new EventRepository($this->entityManagerMock, $this->classMetadata);
    }

    public function testCanSave(): void
    {
        $eventMock = $this->createMock(EventEntity::class);

        $this->entityManagerMock->expects($this->once())->method('persist')->with($eventMock);
        $this->entityManagerMock->expects($this->once())->method('flush');

        $this->subject->save($eventMock);
    }
}
