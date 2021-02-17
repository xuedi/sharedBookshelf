<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Repositories\BookRepository;

class Book implements Entity
{
    private UuidInterface $id;
    private string $author = '';
    private string $country = '';
    private string $language = '';
    private string $pages = '';
    private string $title = '';
    private string $year = '';
    private string $ean = '';

    public function __construct(
        string $author,
        string $country,
        string $language,
        string $pages,
        string $title,
        string $year,
        string $ean
    )
    {
        $this->id = Uuid::uuid4();
        $this->author = $author;
        $this->title = $title;
        $this->ean = $ean;
        $this->country = $country;
        $this->language = $language;
        $this->pages = $pages;
        $this->year = $year;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function loadMetadata(ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->createField('id', 'uuid')->makePrimaryKey()->build();
        $builder->setCustomRepositoryClass(BookRepository::class);
        $builder->addField('author', 'string');
        $builder->addField('country', 'string');
        $builder->addField('language', 'string');
        $builder->addField('pages', 'string');
        $builder->addField('title', 'string');
        $builder->addField('year', 'string');
        $builder->addField('ean', 'string');
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getEan(): string
    {
        return $this->ean;
    }
}
