<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Entities\User;

/**
 * @covers \SharedBookshelf\Entities\User
 */
final class UserTest extends TestCase
{
    public function testCanRetrieveData(): void
    {
        $user = new User("name", "pass", "email");

        $this->assertEquals("name", $user->getUsername());
        $this->assertEquals("pass", $user->getPasswordHash());
        $this->assertEquals("email", $user->getEmail());
        $this->assertEquals(null, $user->getLastLogin());
        $this->assertInstanceOf(UuidInterface::class, $user->getId());
    }
}
