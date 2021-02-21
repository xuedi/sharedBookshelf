<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use DateTime;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\EventType;

class BookHandoverEvent implements Event
{
    private UuidInterface $bookId;
    private UuidInterface $fromId;
    private UuidInterface $toId;
    private DateTime $created;
    private UuidInterface $eventId;

    public static function fromParameters(UuidInterface $bookId, UuidInterface $fromId, UuidInterface $toId): self
    {
        $eventId = Uuid::uuid4();
        $created = new DateTime();
        return new self($bookId, $fromId, $toId, $created, $eventId);
    }

    public static function fromPayload(array $payload, DateTime $created, UuidInterface $eventId): self
    {
        $bookId = (string)$payload['book_id'];
        $fromId = (string)$payload['from_id'];
        $toId = (string)$payload['to_id'];

        return new self(
            Uuid::fromString($bookId),
            Uuid::fromString($fromId),
            Uuid::fromString($toId),
            $created,
            $eventId
        );
    }

    private function __construct(
        UuidInterface $bookId,
        UuidInterface $fromId,
        UuidInterface $toId,
        DateTime $created,
        UuidInterface $eventId
    ) {
        $this->bookId = $bookId;
        $this->fromId = $fromId;
        $this->toId = $toId;
        $this->created = $created;
        $this->eventId = $eventId;
    }

    public function getPayload(): array
    {
        return [
            'book_id' => $this->getBookId()->toString(),
            'from_id' => $this->fromId->toString(),
            'to_id' => $this->toId->toString(),
        ];
    }

    public function getType(): EventType
    {
        return EventType::fromString('book_handover');
    }

    public function getEventId(): UuidInterface
    {
        return $this->eventId;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function getBookId(): UuidInterface
    {
        return $this->bookId;
    }

    public function getFromId(): UuidInterface
    {
        return $this->fromId;
    }

    public function getToId(): UuidInterface
    {
        return $this->toId;
    }
}
