<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

/**
 * @covers \SharedBookshelf\Entities\Event
 */
final class EventTest extends TestCase
{
    public function testCanRetrieveData(): void
    {
        $user = new Event("type", ['test' => 'value']);

        $this->assertEquals("type", $user->getType());
        $this->assertEquals(['test' => 'value'], $user->getPayload());
        $this->assertInstanceOf(DateTime::class, $user->getCreated());
        $this->assertInstanceOf(UuidInterface::class, $user->getId());
    }
}
