<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Entities\UserEntity;

/**
 * @covers \SharedBookshelf\Entities\BookEntity
 * @uses   \SharedBookshelf\Entities\AuthorEntity
 * @uses   \SharedBookshelf\Entities\LanguageEntity
 * @uses   \SharedBookshelf\Entities\CountryEntity
 */
final class BookTest extends TestCase
{
    public function testCanRetrieveData(): void
    {
        $expectedAuthor = new AuthorEntity("xuedi");
        $expectedCountry = new CountryEntity("England");
        $expectedLanguage = new LanguageEntity("English");
        $expectedPages = 1234;
        $expectedTitle = "php coding";
        $expectedYear = 2020;
        $expectedEan = "1002003004009";
        $user = new BookEntity(
            $expectedAuthor,
            $expectedCountry,
            $expectedLanguage,
            $expectedPages,
            $expectedTitle,
            $expectedYear,
            $expectedEan
        );

        $this->assertInstanceOf(UuidInterface::class, $user->getId());
        $this->assertEquals($expectedAuthor, $user->getAuthor());
        $this->assertEquals($expectedCountry, $user->getCountry());
        $this->assertEquals($expectedLanguage, $user->getLanguage());
        $this->assertEquals($expectedPages, $user->getPages());
        $this->assertEquals($expectedTitle, $user->getTitle());
        $this->assertEquals($expectedYear, $user->getYear());
        $this->assertEquals($expectedEan, $user->getEan());
    }
}
