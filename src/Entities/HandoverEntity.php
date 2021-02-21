<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use DateTime;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Repositories\HandoverRepository;

class HandoverEntity implements Entity
{
    private UuidInterface $id;
    private BookEntity $book;
    private UserEntity $requestedBy;
    private UserEntity $requestedFrom;
    private DateTime $requestedAt;
    private ?DateTime $bookSendAt = null;
    private ?DateTime $bookReceivedAt = null;


    public function __construct(
        BookEntity $book,
        UserEntity $requestedBy,
        UserEntity $requestedFrom,
        DateTime $requestedAt,
        ?DateTime $bookSendAt = null,
        ?DateTime $bookReceivedAt = null
    ) {
        $this->id = Uuid::uuid4();
        $this->book = $book;
        $this->requestedBy = $requestedBy;
        $this->requestedFrom = $requestedFrom;
        $this->requestedAt = $requestedAt;
        $this->bookSendAt = $bookSendAt;
        $this->bookReceivedAt = $bookReceivedAt;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function loadMetadata(ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('Handover');
        $builder->createField('id', 'uuid')->makePrimaryKey()->build();
        $builder->setCustomRepositoryClass(HandoverRepository::class);
        $builder->addManyToOne('book', BookEntity::class);
        $builder->addManyToOne('requestedBy', UserEntity::class);
        $builder->addManyToOne('requestedFrom', UserEntity::class);
        $builder->addField('requestedAt', 'datetime');
        $builder->addField('bookSendAt', 'datetime', ['nullable' => true]);
        $builder->addField('bookReceivedAt', 'datetime', ['nullable' => true]);
    }

    public function setBookSendAt(DateTime $bookSendAt): void
    {
        $this->bookSendAt = $bookSendAt;
    }

    public function setBookReceivedAt(DateTime $bookReceivedAt): void
    {
        $this->bookReceivedAt = $bookReceivedAt;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getBook(): BookEntity
    {
        return $this->book;
    }

    public function getRequestedBy(): UserEntity
    {
        return $this->requestedBy;
    }

    public function getRequestedFrom(): UserEntity
    {
        return $this->requestedFrom;
    }

    public function getRequestedAt(): DateTime
    {
        return $this->requestedAt;
    }

    public function getBookSendAt(): ?DateTime
    {
        return $this->bookSendAt;
    }

    public function getBookReceivedAt(): ?DateTime
    {
        return $this->bookReceivedAt;
    }
}
