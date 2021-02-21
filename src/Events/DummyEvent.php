<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use DateTime;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\EventType;

class DummyEvent implements Event
{
    private DateTime $created;
    private UuidInterface $eventId;

    public static function generate(): self
    {
        $eventId = Uuid::uuid4();
        $created = new DateTime();
        return new self($created, $eventId);
    }

    public static function fromPayload(array $payload, DateTime $created, UuidInterface $eventId): Event
    {
        return new self($created, $eventId);
    }

    private function __construct(DateTime $created, UuidInterface $eventId)
    {
        $this->created = $created;
        $this->eventId = $eventId;
    }

    public function getPayload(): array
    {
        return [];
    }

    public function getType(): EventType
    {
        return EventType::fromString('dummy');
    }

    public function getEventId(): UuidInterface
    {
        return $this->eventId;
    }
}
