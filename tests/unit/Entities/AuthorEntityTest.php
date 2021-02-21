<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use PHPUnit\Framework\TestCase;
use SharedBookshelf\Entities\AuthorEntity;

/**
 * @covers \SharedBookshelf\Entities\AuthorEntity
 */
final class AuthorEntityTest extends TestCase
{
    use ReflectiveSetterForId;

    public function testCanRetrieveData(): void
    {
        $user = new AuthorEntity("name");
        $this->setDoctrineId($user, 20);

        $this->assertEquals(20, $user->getId());
        $this->assertEquals("name", $user->getName());
    }
}
