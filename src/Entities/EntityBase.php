<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Ramsey\Uuid\Uuid;

abstract class EntityBase
{
    private Uuid $id;
    protected static ClassMetadataBuilder $builder;

    public static function loadMetadata(ClassMetadata $metadata): void
    {
        self::$builder = new ClassMetadataBuilder($metadata);
        self::$builder->createField('id', 'uuid')->makePrimaryKey()->generatedValue()->build();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
