<?php declare(strict_types=1);

namespace SharedBookshelf\Fixtures;

use RuntimeException;
use SharedBookshelf\Entities\AuthorEntity;
use SharedBookshelf\Entities\BookEntity;
use SharedBookshelf\Entities\CountryEntity;
use SharedBookshelf\Entities\LanguageEntity;
use SharedBookshelf\Entities\UserEntity;

trait TypedReferenceGetter
{
    private function getUser(string $string): UserEntity
    {
        $reference = $this->getReference('USER_' . md5($string));
        if (!$reference instanceof UserEntity) {
            throw new RuntimeException('Wrong class');
        }
        return $reference;
    }

    private function getAuthor(string $string): AuthorEntity
    {
        $reference = $this->getReference('AUTHOR_' . md5($string));
        if (!$reference instanceof AuthorEntity) {
            throw new RuntimeException('Wrong class');
        }
        return $reference;
    }

    private function getLanguage(string $string): LanguageEntity
    {
        $reference = $this->getReference('LANGUAGE_' . md5($string));
        if (!$reference instanceof LanguageEntity) {
            throw new RuntimeException('Wrong class');
        }
        return $reference;
    }

    private function getCountry(string $string): CountryEntity
    {
        $reference = $this->getReference('COUNTRY_' . md5($string));
        if (!$reference instanceof CountryEntity) {
            throw new RuntimeException('Wrong class');
        }
        return $reference;
    }

    private function getBook(string $author, string $title): BookEntity
    {
        $reference = $this->getReference('BOOK_'.md5($author.$title));
        if (!$reference instanceof BookEntity) {
            throw new RuntimeException('Wrong class');
        }
        return $reference;
    }
}
