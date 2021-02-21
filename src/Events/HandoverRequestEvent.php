<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use DateTime;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\EventType;

class HandoverRequestEvent implements Event
{
    private DateTime $created;
    private UuidInterface $requestedBy;
    private UuidInterface $requestedFrom;
    private UuidInterface $bookId;
    private UuidInterface $eventId;

    public static function fromParameters(UuidInterface $requestedBy, UuidInterface $requestedFrom, UuidInterface $bookId): self
    {
        $eventId = Uuid::uuid4();
        $created = new DateTime();
        return new self($requestedBy, $requestedFrom, $bookId, $created, $eventId);
    }

    public static function fromPayload(array $payload, DateTime $created, UuidInterface $eventId): self
    {
        $requestedBy = (string)$payload['requestedBy'];
        $requestedFrom = (string)$payload['requestedFrom'];
        $bookId = (string)$payload['bookId'];

        return new self(
            Uuid::fromString($requestedBy),
            Uuid::fromString($requestedFrom),
            Uuid::fromString($bookId),
            $created,
            $eventId
        );
    }

    private function __construct(
        UuidInterface $requestedBy,
        UuidInterface $requestedFrom,
        UuidInterface $bookId,
        DateTime $created,
        UuidInterface $eventId
    ) {
        $this->requestedBy = $requestedBy;
        $this->requestedFrom = $requestedFrom;
        $this->bookId = $bookId;
        $this->created = $created;
        $this->eventId = $eventId;
    }

    public function getPayload(): array
    {
        return [
            'requestedBy' => $this->requestedBy->toString(),
            'requestedFrom' => $this->getRequestedFrom()->toString(),
            'bookId' => $this->getBookId()->toString(),
        ];
    }

    public function getType(): EventType
    {
        return EventType::fromString('handover_request');
    }

    public function getRequestedBy(): UuidInterface
    {
        return $this->requestedBy;
    }

    public function getRequestedFrom(): UuidInterface
    {
        return $this->requestedFrom;
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
