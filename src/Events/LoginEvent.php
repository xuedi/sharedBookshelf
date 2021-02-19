<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Entities\User;
use SharedBookshelf\EventType;
use SharedBookshelf\IpAddress;

class LoginEvent implements Event
{
    private UuidInterface $userId;
    private IpAddress $ip;

    public static function fromParameters(UuidInterface $userId, IpAddress $ip): self
    {
        return new self($userId, $ip);
    }

    public static function fromPayload(array $payload): self
    {
        $ip = (string)$payload['ip'];
        $userId = (string)$payload['userId'];

        return new self(
            Uuid::fromString($userId),
            IpAddress::fromString($ip)
        );
    }

    private function __construct(UuidInterface $userId, IpAddress $ip)
    {
        $this->userId = $userId;
        $this->ip = $ip;
    }

    public function asPayload(): array
    {
        return [
            'userId' => $this->userId->toString(),
            'ip' => $this->ip->asString(),
        ];
    }

    public function getType(): EventType
    {
        return EventType::fromString('login');
    }
}
