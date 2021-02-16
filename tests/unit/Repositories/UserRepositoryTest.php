<?php declare(strict_types=1);

namespace SharedBookshelf;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SharedBookshelf\Entities\User;
use SharedBookshelf\Repositories\UserRepository;

/**
 * @covers \SharedBookshelf\Repositories\UserRepository
 */
final class UserRepositoryTest extends TestCase
{
    private UserRepository $subject;
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

        $this->subject = new UserRepository($this->entityManagerMock, $this->classMetadata);
    }

    public function testCanFindUsername(): void
    {
        $expectedUsername = 'xuedi';
        $expectedStatement = 'SELECT * FROM AppBundle\Entity\User WHERE username LIKE :username';

        $this->queryMock
            ->expects($this->once())
            ->method('setParameter')
            ->with('username', $expectedUsername, null);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('createQuery')
            ->with($expectedStatement)
            ->willReturn($this->queryMock);

        $this->subject->findByUsername($expectedUsername);
    }

    public function testCanSave(): void
    {
        $userMock = $this->createMock(User::class);

        $this->entityManagerMock->expects($this->once())->method('persist')->with($userMock);
        $this->entityManagerMock->expects($this->once())->method('flush');

        $this->subject->save($userMock);
    }
}
