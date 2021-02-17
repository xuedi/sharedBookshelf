<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Repositories\UserRepository;

class User implements Entity
{
    private UuidInterface $id;
    private string $username = '';
    private string $passwordHash = '';
    private string $email = '';

    public function __construct(string $username, string $password, string $email)
    {
        $this->id = Uuid::uuid4();
        $this->username = $username;
        $this->passwordHash = $password;
        $this->email = $email;
    }

    /**
     * @codeCoverageIgnore
     */
    public static function loadMetadata(ClassMetadata $metadata): void
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->createField('id', 'uuid')->makePrimaryKey()->build();
        $builder->setCustomRepositoryClass(UserRepository::class);
        $builder->addField('username', 'string');
        $builder->addField('passwordHash', 'string');
        $builder->addField('email', 'string');
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
