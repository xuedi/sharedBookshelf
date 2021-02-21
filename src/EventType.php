<?php declare(strict_types=1);

namespace SharedBookshelf;

use RuntimeException;

class EventType
{
    const DUMMY = 'dummy';
    const SIGNUP = 'signup';
    const LOGIN = 'login';
    const BOOK_REQUEST = 'book_request';
    const BOOK_HANDOVER = 'book_handover';
    const BOOK_RECEIVED = 'book_received';

    private static array $validTypes = [
        self::DUMMY,
        self::SIGNUP,
        self::LOGIN,
        self::BOOK_REQUEST,
        self::BOOK_HANDOVER,
        self::BOOK_RECEIVED,
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
