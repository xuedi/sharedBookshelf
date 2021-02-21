<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use DateTime;
use JetBrains\PhpStorm\ArrayShape;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\EventType;
use SharedBookshelf\IpAddress;

class LoginEvent implements Event
{
    private UuidInterface $eventId;
    private UuidInterface $userId;
    private IpAddress $ip;
    private DateTime $created;

    public static function fromParameters(UuidInterface $userId, IpAddress $ip): self
    {
        $eventId = Uuid::uuid4();
        $created = new DateTime();
        return new self($userId, $ip, $created, $eventId);
    }

    public static function fromPayload(array $payload, DateTime $created, UuidInterface $eventId): self
    {
        $ip = (string)$payload['ip'];
        $userId = (string)$payload['userId'];

        return new self(
            Uuid::fromString($userId),
            IpAddress::fromString($ip),
            $created,
            $eventId
        );
    }

    private function __construct(UuidInterface $userId, IpAddress $ip, DateTime $created, UuidInterface $eventId)
    {
        $this->userId = $userId;
        $this->ip = $ip;
        $this->created = $created;
        $this->eventId = $eventId;
    }

    public function getPayload(): array
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

    public function getEventId(): UuidInterface
    {
        return $this->eventId;
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
