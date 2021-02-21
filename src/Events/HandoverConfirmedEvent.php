<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use DateTime;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\EventType;

class HandoverConfirmedEvent implements Event
{
    private DateTime $created;
    private UuidInterface $userId;
    private UuidInterface $bookId;
    private UuidInterface $eventId;

    public static function fromParameters(UuidInterface $userId, UuidInterface $bookId): self
    {
        $eventId = Uuid::uuid4();
        $created = new DateTime();
        return new self($userId, $bookId, $created, $eventId);
    }

    public static function fromPayload(array $payload, DateTime $created, UuidInterface $eventId): self
    {
        $userId = (string)$payload['userId'];
        $bookId = (string)$payload['bookId'];

        return new self(Uuid::fromString($userId), Uuid::fromString($bookId), $created, $eventId);
    }

    private function __construct(
        UuidInterface $userId,
        UuidInterface $bookId,
        DateTime $created,
        UuidInterface $eventId
    ) {
        $this->userId = $userId;
        $this->bookId = $bookId;
        $this->created = $created;
        $this->eventId = $eventId;
    }

    public function getPayload(): array
    {
        return [
            'userId' => $this->userId->toString(),
            'bookId' => $this->getBookId()->toString(),
        ];
    }

    public function getType(): EventType
    {
        return EventType::fromString('handover_confirmed');
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
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
