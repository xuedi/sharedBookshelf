<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use DateTime;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\EventType;

class HandoverStartedEvent implements Event
{
    private DateTime $created;
    private UuidInterface $userId;
    private UuidInterface $handoverTo;
    private UuidInterface $bookId;
    private UuidInterface $eventId;

    public static function fromParameters(UuidInterface $userId, UuidInterface $handoverTo, UuidInterface $bookId): self
    {
        $eventId = Uuid::uuid4();
        $created = new DateTime();
        return new self($userId, $handoverTo, $bookId, $created, $eventId);
    }

    public static function fromPayload(array $payload, DateTime $created, UuidInterface $eventId): self
    {
        $userId = (string)$payload['userId'];
        $handoverTo = (string)$payload['handoverTo'];
        $bookId = (string)$payload['bookId'];

        return new self(
            Uuid::fromString($userId),
            Uuid::fromString($handoverTo),
            Uuid::fromString($bookId),
            $created,
            $eventId
        );
    }

    private function __construct(
        UuidInterface $userId,
        UuidInterface $handoverTo,
        UuidInterface $bookId,
        DateTime $created,
        UuidInterface $eventId
    ) {
        $this->userId = $userId;
        $this->bookId = $bookId;
        $this->created = $created;
        $this->eventId = $eventId;
        $this->handoverTo = $handoverTo;
    }

    public function getPayload(): array
    {
        return [
            'userId' => $this->userId->toString(),
            'handoverTo' => $this->handoverTo->toString(),
            'bookId' => $this->getBookId()->toString(),
        ];
    }

    public function getType(): EventType
    {
        return EventType::fromString('handover_started');
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    public function getHandoverTo(): UuidInterface
    {
        return $this->handoverTo;
    }

    public function getBookId(): UuidInterface
    {
        return $this->bookId;
    }

    public function getEventId(): UuidInterface
    {
        return $this->eventId;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }
}
