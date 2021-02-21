<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use ArrayIterator;
use DateTime;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Events\DummyEvent;
use SharedBookshelf\EventType;

/**
 * @covers \SharedBookshelf\Entities\EventEntityCollection
 * @uses   \SharedBookshelf\EventType
 * @uses   \SharedBookshelf\Events\DummyEvent
 */
final class EventEntityCollectionTest extends TestCase
{
    public function testCanRetrieveData(): void
    {
        $eventMock = $this->createMock(EventEntity::class);

        $subject = new EventEntityCollection();
        $subject->add($eventMock);

        $this->assertEquals(new ArrayIterator([$eventMock]), $subject->getIterator());
    }
}
