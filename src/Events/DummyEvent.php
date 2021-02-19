<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Entities\User;
use SharedBookshelf\EventType;

class DummyEvent implements Event
{
    private string $dummy;

    public static function generate(): self
    {
        return new self('test');
    }

    public static function fromPayload(array $payload): self
    {
        $data = (string)$payload['dummyValue'];
        return new self($data);
    }

    private function __construct(string $dummy)
    {
        $this->dummy = $dummy;
    }

    public function asPayload(): array
    {
        return [
            'dummyValue' => $this->dummy
        ];
    }

    public function getType(): EventType
    {
        return EventType::fromString('login');
    }
}
