<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use DateTime;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\EventType;

class BookRequestEvent implements Event
{
    private DateTime $created;
    private UuidInterface $userId;
    private UuidInterface $bookId;
    private UuidInterface $eventId;

    public static function fromParameters(UuidInterface $bookId, UuidInterface $userId): self
    {
        $eventId = Uuid::uuid4();
        $created = new DateTime();
        return new self($bookId, $userId, $created, $eventId);
    }

    public static function fromPayload(array $payload, DateTime $created, UuidInterface $eventId): self
    {
        $userId = (string)$payload['user_id'];
        $bookId = (string)$payload['book_id'];

        return new self(
            Uuid::fromString($bookId),
            Uuid::fromString($userId),
            $created,
            $eventId
        );
    }

    private function __construct(
        UuidInterface $bookId,
        UuidInterface $userId,
        DateTime $created,
        UuidInterface $eventId
    ) {
        $this->bookId = $bookId;
        $this->userId = $userId;
        $this->created = $created;
        $this->eventId = $eventId;
    }

    public function getPayload(): array
    {
        return [
            'book_id' => $this->bookId->toString(),
            'user_id' => $this->userId->toString(),
        ];
    }

    public function getType(): EventType
    {
        return EventType::fromString('book_request');
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

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }
}
