<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Events\DummyEvent;

/**
 * @covers \SharedBookshelf\Entities\Event
 * @uses   \SharedBookshelf\EventType
 * @uses   \SharedBookshelf\Events\DummyEvent
 */
final class EventTest extends TestCase
{
    public function testCanRetrieveData(): void
    {
        $eventPayload = DummyEvent::generate();

        $event = new Event($eventPayload);

        $this->assertEquals($eventPayload->getType(), $event->getType());
        $this->assertEquals([], $event->getPayload());
        $this->assertInstanceOf(DateTime::class, $event->getCreated());
        $this->assertInstanceOf(UuidInterface::class, $event->getId());
    }
}
