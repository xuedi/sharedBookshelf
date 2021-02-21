<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

/**
 * @covers \SharedBookshelf\Entities\UserEntity
 */
final class UserEntityTest extends TestCase
{
    public function testCanRetrieveData(): void
    {
        $user = new UserEntity("name", "pass", "email");

        $this->assertEquals("name", $user->getUsername());
        $this->assertEquals("pass", $user->getPasswordHash());
        $this->assertEquals("email", $user->getEmail());
        $this->assertEquals(null, $user->getLastLogin());
        $this->assertInstanceOf(UuidInterface::class, $user->getId());
    }

    public function testSetLastLogin(): void
    {
        $expectedDateTime = new DateTime();

        $user = new UserEntity("name", "pass", "email");
        $user->setLastLogin($expectedDateTime);

        $this->assertEquals($expectedDateTime, $user->getLastLogin());
    }
}
