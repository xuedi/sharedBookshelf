<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Events\DummyEvent;
use SharedBookshelf\EventType;

/**
 * @covers \SharedBookshelf\Entities\EventEntity
 * @uses   \SharedBookshelf\EventType
 * @uses   \SharedBookshelf\Events\DummyEvent
 */
final class EventTest extends TestCase
{
    public function testCanRetrieveData(): void
    {
        $eventPayload = DummyEvent::generate();

        $event = new EventEntity($eventPayload->getType(), $eventPayload->getPayload());

        $this->assertEquals($eventPayload->getType(), $event->getType());
        $this->assertEquals([], $event->getPayload());
        $this->assertInstanceOf(DateTime::class, $event->getCreated());
        $this->assertInstanceOf(UuidInterface::class, $event->getId());
    }
}
