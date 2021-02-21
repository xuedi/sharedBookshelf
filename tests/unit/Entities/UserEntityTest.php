<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Entities\UserEntity;

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
}
