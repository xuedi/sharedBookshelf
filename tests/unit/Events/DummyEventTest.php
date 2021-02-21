<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\EventType;

/**
 * @covers \SharedBookshelf\Events\DummyEvent
 * @uses   \SharedBookshelf\EventType
 */
final class DummyEventTest extends TestCase
{
    private EventType $expectedType;
    private DummyEvent $subject;

    public function setUp(): void
    {
        $this->expectedType = EventType::fromString('dummy');
        $this->subject = DummyEvent::generate();
    }

    public function testCanRetrieveData(): void
    {
        $this->assertEquals($this->expectedType, $this->subject->getType());
        $this->assertEquals([], $this->subject->getPayload());
        $this->assertInstanceOf(DateTime::class, $this->subject->getCreated());
        $this->assertInstanceOf(UuidInterface::class, $this->subject->getEventId());
    }

    public function testBuildFromPayload(): void
    {
        $subject = DummyEvent::fromPayload([], new DateTime(), Uuid::uuid4());

        $this->assertEquals($this->expectedType, $subject->getType());
    }
}
