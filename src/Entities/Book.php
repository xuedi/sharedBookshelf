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
    private Author $author;
    private string $country = '';
    private Language $language;
    private int $pages = 0;
    private string $title = '';
    private int $year = 0;
    private string $ean = '';

    public function __construct(
        Author $author,
        string $country,
        Language $language,
        int $pages,
        string $title,
        int $year,
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
        $builder->addManyToOne('author', 'Author');
        $builder->addField('country', 'string');
        $builder->addManyToOne('language', 'Language');
        $builder->addField('pages', 'smallint');
        $builder->addField('title', 'string');
        $builder->addField('year', 'smallint');
        $builder->addField('ean', 'string');
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getAuthor(): Author
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
