<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use Doctrine\ORM\Mapping\ClassMetadata;
use SharedBookshelf\Repositories\UserRepository;

class User extends EntityBase implements Entity
{
    private string $username = '';
    private string $password = '';

    public static function loadMetadata(ClassMetadata $metadata): void
    {
        self::$builder->setCustomRepositoryClass(UserRepository::class);
        self::$builder->addField('username', 'string');
        self::$builder->addField('password', 'string');
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
