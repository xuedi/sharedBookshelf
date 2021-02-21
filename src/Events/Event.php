<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use DateTime;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\EventType;

interface Event
{
    public function getPayload(): array;

    public function getType(): EventType;

    public static function fromPayload(array $payload, DateTime $created, UuidInterface $eventId): self;
}
