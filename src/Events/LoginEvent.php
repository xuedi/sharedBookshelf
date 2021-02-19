<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Entities\User;

class LoginEvent implements Event
{
    private UuidInterface $userId;

    public static function fromParameters(User $user): self
    {
        return new self($user->getId());
    }

    public static function fromPayload(array $payload): self
    {
        $userId = (string)$payload['userId'];
        return new self(Uuid::fromString($userId));
    }

    private function __construct(UuidInterface $userId)
    {
        $this->userId = $userId;
    }

    public function asPayload(): array
    {
        return [
            'userId' => $this->userId
        ];
    }
}
