<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Repositories\BookRepository;

class BookEntity implements Entity
{
    private UuidInterface $id;
    private UserEntity $location;
    private AuthorEntity $author;
    private CountryEntity $country;
    private LanguageEntity $language;
    private int $pages = 0;
    private string $title = '';
    private int $year = 0;
    private string $ean = '';

    public function __construct(
        UserEntity $location,
        AuthorEntity $author,
        CountryEntity $country,
        LanguageEntity $language,
        int $pages,
        string $title,
        int $year,
        string $ean
    ) {
        $this->id = Uuid::uuid4();
        $this->location = $location;
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
        $builder->setTable('Book');
        $builder->createField('id', 'uuid')->makePrimaryKey()->build();
        $builder->setCustomRepositoryClass(BookRepository::class);
        $builder->addManyToOne('location', UserEntity::class);
        $builder->addManyToOne('author', AuthorEntity::class);
        $builder->addManyToOne('country', CountryEntity::class);
        $builder->addManyToOne('language', LanguageEntity::class);
        $builder->addField('pages', 'smallint');
        $builder->addField('title', 'string');
        $builder->addField('year', 'smallint');
        $builder->addField('ean', 'string');
    }

    public function setLocation(UserEntity $location): void
    {
        $this->location = $location;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getLocation(): UserEntity
    {
        return $this->location;
    }

    public function getAuthor(): AuthorEntity
    {
        return $this->author;
    }

    public function getCountry(): CountryEntity
    {
        return $this->country;
    }

    public function getLanguage(): LanguageEntity
    {
        return $this->language;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getEan(): string
    {
        return $this->ean;
    }
}
