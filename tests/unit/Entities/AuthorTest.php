<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use PHPUnit\Framework\TestCase;
use SharedBookshelf\Entities\Author;

/**
 * @covers \SharedBookshelf\Entities\Author
 */
final class AuthorTest extends TestCase
{
    use ReflectiveSetterForId;

    public function testCanRetrieveData(): void
    {
        $user = new Author("name");
        $this->setDoctrineId($user, 20);

        $this->assertEquals(20, $user->getId());
        $this->assertEquals("name", $user->getName());
    }
}
