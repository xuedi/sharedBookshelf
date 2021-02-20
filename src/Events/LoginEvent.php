<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use DateTime;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\EventType;
use SharedBookshelf\IpAddress;

class LoginEvent implements Event
{
    private UuidInterface $userId;
    private IpAddress $ip;
    private DateTime $created;

    public static function fromParameters(UuidInterface $userId, IpAddress $ip): self
    {
        return new self($userId, $ip, new DateTime());
    }

    public static function fromPayload(array $payload, DateTime $created): self
    {
        $ip = (string)$payload['ip'];
        $userId = (string)$payload['userId'];

        return new self(
            Uuid::fromString($userId),
            IpAddress::fromString($ip),
            $created
        );
    }

    private function __construct(UuidInterface $userId, IpAddress $ip, DateTime $created)
    {
        $this->userId = $userId;
        $this->ip = $ip;
        $this->created = $created;
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

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    public function getIp(): IpAddress
    {
        return $this->ip;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }
}
