<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Entities\User;
use SharedBookshelf\EventType;

class DummyEvent implements Event
{
    public static function generate(): self
    {
        return new self();
    }

    public static function fromPayload(array $payload): self
    {
        return new self();
    }
    
    private function __construct()
    {
        //
    }

    public function asPayload(): array
    {
        return [];
    }

    public function getType(): EventType
    {
        return EventType::fromString('dummy');
    }
}
