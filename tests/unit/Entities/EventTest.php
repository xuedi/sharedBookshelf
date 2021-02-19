<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Events\DummyEvent;
use SharedBookshelf\EventType;

/**
 * @covers \SharedBookshelf\Entities\Event
 * @uses   \SharedBookshelf\EventType
 * @uses   \SharedBookshelf\Events\DummyEvent
 */
final class EventTest extends TestCase
{
    public function testCanRetrieveData(): void
    {
        $eventType = EventType::fromString("dummy");
        $eventPayload = DummyEvent::generate();

        $event = new Event($eventType, $eventPayload);

        $this->assertEquals($eventType, $event->getType());
        $this->assertEquals(['dummyValue' => 'test'], $event->getPayload());
        $this->assertInstanceOf(DateTime::class, $event->getCreated());
        $this->assertInstanceOf(UuidInterface::class, $event->getId());
    }
}
