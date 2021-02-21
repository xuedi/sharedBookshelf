<?php declare(strict_types=1);

namespace SharedBookshelf;

use RuntimeException;

class EventType
{
    const DUMMY = 'dummy';
    const SIGNUP = 'signup';
    const LOGIN = 'login';

    private static array $validTypes = [
        self::DUMMY,
        self::SIGNUP,
        self::LOGIN,
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
