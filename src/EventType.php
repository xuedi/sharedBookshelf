<?php declare(strict_types=1);

namespace SharedBookshelf;

use RuntimeException;

class EventType
{
    private static array $validTypes = [
        'dummy',
        'signup',
        'login',
    ];
    private string $type;

    public static function fromString(string $type): self
    {
        return new self($type);
    }

    private function __construct(string $type)
    {
        $this->ensureIsValid($type);
        $this->type = $type;
    }

    public function asString(): string
    {
        return $this->type;
    }

    private function ensureIsValid(string $type): void
    {
        if (!in_array($type, self::$validTypes)) {
            throw new RuntimeException("Invalid type: '$type'");
        }
    }
}
