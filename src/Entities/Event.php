<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use DateTime;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Events\Event as EventInterface;
use SharedBookshelf\EventType;
use SharedBookshelf\Repositories\EventRepository;

class Event implements Entity
{
    private UuidInterface $id;
    private string $type;
    private DateTime $created;
    private string $payload;

    public function __construct(EventType $type, EventInterface $event)
    {
        $this->id = Uuid::uuid4();
        $this->type = $type->asString();
        $this->created = new DateTime('now');
        $this->payload = json_encode($event->asPayload());
    }

    /**
     * @codeCoverageIgnore
     */
    public static function loadMetadata(ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->createField('id', 'uuid')->makePrimaryKey()->build();
        $builder->setCustomRepositoryClass(EventRepository::class);
        $builder->addField('type', 'string');
        $builder->addField('payload', 'text');
        $builder->addField('created', 'datetime');
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getType(): EventType
    {
        return EventType::fromString($this->type);
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function getPayload(): array
    {
        return (array)json_decode($this->payload, true);
    }
}
