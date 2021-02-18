<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Repositories\BookRepository;

class Language implements Entity
{
    private int $id;
    private string $name = '';

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function loadMetadata(ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->createField('id', 'integer')->isPrimaryKey()->generatedValue()->build();
        $builder->setCustomRepositoryClass(BookRepository::class);
        $builder->addField('name', 'string');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

}