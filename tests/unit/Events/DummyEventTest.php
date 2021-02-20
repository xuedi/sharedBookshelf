<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use PHPUnit\Framework\TestCase;
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

    public function testCanGetEventType(): void
    {
        $this->assertEquals($this->expectedType, $this->subject->getType());
    }

    public function testBuildFromPayload(): void
    {
        $subject = DummyEvent::fromPayload([]);

        $this->assertEquals($this->expectedType, $subject->getType());
    }

    public function testGetPayload(): void
    {
        $this->assertEquals([], $this->subject->asPayload());
    }
}
