<?php declare(strict_types=1);

namespace SharedBookshelf\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SharedBookshelf\Entities\User;

/**
 * @covers \SharedBookshelf\Repositories\BookRepository
 */
final class BookRepositoryTest extends TestCase
{
    private BookRepository $subject;
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

        $this->subject = new BookRepository($this->entityManagerMock, $this->classMetadata);
    }

    public function testCanSave(): void
    {
        $userMock = $this->createMock(User::class);

        $this->entityManagerMock->expects($this->once())->method('persist')->with($userMock);
        $this->entityManagerMock->expects($this->once())->method('flush');

        $this->subject->save($userMock);
    }
}
