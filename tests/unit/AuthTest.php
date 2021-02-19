<?php declare(strict_types=1);

namespace SharedBookshelf;

use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use SharedBookshelf\Entities\User;

/**
 * @covers \SharedBookshelf\Auth
 */
final class AuthTest extends TestCase
{
    private Auth $subject;
    private MockObject|EntityRepository $entityManagerMock;
    private string $expectedUsername;
    private UuidInterface $expectedUuid;

    public function setUp(): void
    {
        $this->expectedUsername = 'unitTestUsername';
        $this->expectedUuid = Uuid::fromString('20256df6-7d38-4e09-893f-c2f1e031150c');

        $_SESSION['auth_username'] = $this->expectedUsername;
        $_SESSION['auth_user_id'] = $this->expectedUuid->toString();

        $this->entityManagerMock = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();
        $this->subject = new Auth($this->entityManagerMock);
    }

    public function testCanRestoreFromSession(): void
    {
        $this->assertTrue($this->subject->hasId());
        $this->assertEquals($this->expectedUuid, $this->subject->getId());
        $this->assertEquals($this->expectedUsername, $this->subject->getUsername());
    }

    public function testCanLogin(): void
    {
        $expectedUsername = 'localNewUsername';
        $expectedUuid = Uuid::uuid4();

        // just check initial setup values
        $this->assertTrue($this->subject->hasId());
        $this->assertEquals($this->expectedUuid, $this->subject->getId());
        $this->assertEquals($this->expectedUsername, $this->subject->getUsername());

        // TRIGGER
        $this->subject->login($expectedUuid, $expectedUsername);

        // object was set
        $this->assertTrue($this->subject->hasId());
        $this->assertEquals($expectedUuid, $this->subject->getId());
        $this->assertEquals($expectedUsername, $this->subject->getUsername());

        // session was also set
        $this->assertEquals($expectedUuid->toString(), $_SESSION['auth_user_id']);
        $this->assertEquals($expectedUsername, $_SESSION['auth_username']);
    }

    public function testCanLogout(): void
    {
        $expectedUsername = 'guest';

        // just check initial setup values
        $this->assertTrue($this->subject->hasId());
        $this->assertEquals($this->expectedUuid, $this->subject->getId());
        $this->assertEquals($this->expectedUsername, $this->subject->getUsername());

        // TRIGGER
        $this->subject->logout();

        // object was set
        $this->assertFalse($this->subject->hasId());
        $this->assertEquals($expectedUsername, $this->subject->getUsername());

        // session was also set
        $this->assertNull($_SESSION['auth_user_id']);
        $this->assertEquals($expectedUsername, $_SESSION['auth_username']);
    }

    public function testCanNotRestoreWithMissingUserNameSession(): void
    {
        unset($_SESSION['auth_username']);
        $_SESSION['auth_user_id'] = '1f090736-259e-4441-b934-7e18ddb549bd';

        $subject = new Auth($this->entityManagerMock);
        $this->assertFalse($subject->hasId());
        $this->assertEquals('guest', $subject->getUsername());
    }

    public function testCanNotRestoreWithMissingUserIdSession(): void
    {
        $_SESSION['auth_username'] = 'test';
        unset($_SESSION['auth_user_id']);

        $subject = new Auth($this->entityManagerMock);
        $this->assertFalse($subject->hasId());
        $this->assertEquals('guest', $subject->getUsername());
    }

    public function testCanNotRestoreFromSessionSinceDueToInvalidUuid(): void
    {
        $_SESSION['auth_user_id'] = 'INVALID';
        $subject = new Auth($this->entityManagerMock);

        $this->assertFalse($subject->hasId());
        $this->assertEquals('guest', $subject->getUsername());

        $this->expectExceptionObject(new RuntimeException('Please check if hadId first'));

        $subject->getId(); // triggers exception
    }

    public function testCanVerify(): void
    {
        $passwordHash = '$2y$12$hlQCjqFyLa.n5GOj0NIvkeJPHtTKTvuV6YVTMmeKwhVvovtWV1BlG'; // matching to: admin
        $expectedUuid = Uuid::fromString('56ad8cc5-6fb8-4896-b703-80cefc7e99e7');

        $loginUser = 'username';
        $loginPass = 'admin';

        $userMock = $this->createMock(User::class);
        $userMock->expects($this->once())->method('getPasswordHash')->willReturn($passwordHash);
        $userMock->expects($this->once())->method('getId')->willReturn($expectedUuid);
        $userMock->expects($this->once())->method('getUsername')->willReturn($loginUser);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['username' => $loginUser])
            ->willReturn($userMock);

        $this->subject->logout(); // make sure is logged out
        $this->assertTrue($this->subject->verify($loginUser, $loginPass));
    }

    public function testDoNotVerifyWhenLoggedInAlready(): void
    {
        $this->entityManagerMock->expects($this->never())->method('findOneBy');

        // still confirm logged in status with true
        $this->assertTrue($this->subject->verify('test', 'test'));
    }

    public function testDoNotVerifyWhenUnknownUser(): void
    {
        $this->entityManagerMock->expects($this->once())->method('findOneBy')->willReturn(null);

        $this->subject->logout(); // make sure is logged out
        $this->assertFalse($this->subject->verify('test', 'test'));
    }

    public function testCanNotVerifyWithWrongCredentials(): void
    {
        $passwordHash = '$2y$12$hlQCjqFyLa.n5GOj0NIvkeJPHtTKTvuV6YVTMmeKwhVvovtWV1BlG'; // matching to: admin
        $loginUser = 'username';
        $loginPass = 'thisIsNotAdmin';

        $userMock = $this->createMock(User::class);
        $userMock->expects($this->once())->method('getPasswordHash')->willReturn($passwordHash);
        $userMock->expects($this->never())->method('getId');
        $userMock->expects($this->never())->method('getUsername');

        $this->entityManagerMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['username' => $loginUser])
            ->willReturn($userMock);

        $this->subject->logout(); // make sure is logged out
        $this->assertFalse($this->subject->verify($loginUser, $loginPass));
    }
}
