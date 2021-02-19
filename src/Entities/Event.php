<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use DateTime;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Repositories\EventRepository;

class Event implements Entity
{
    private UuidInterface $id;
    private string $type;
    private DateTime $created;
    private array $payload;

    public function __construct(string $type, array $payload)
    {
        $this->id = Uuid::uuid4();
        $this->type = $type;
        $this->created = new DateTime();
        $this->payload = $payload;
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

    public function getType(): string
    {
        return $this->type;
    }

    public function getCreated(): DateTime
    {
        return $this->created;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
