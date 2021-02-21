<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

/**
 * @covers \SharedBookshelf\Entities\BookEntity
 * @uses   \SharedBookshelf\Entities\AuthorEntity
 * @uses   \SharedBookshelf\Entities\LanguageEntity
 * @uses   \SharedBookshelf\Entities\CountryEntity
 */
final class BookEntityTest extends TestCase
{
    public function testCanRetrieveData(): void
    {
        $expectedUserMock = $this->createMock(UserEntity::class);
        $expectedAuthor = new AuthorEntity("xuedi");
        $expectedCountry = new CountryEntity("England");
        $expectedLanguage = new LanguageEntity("English");
        $expectedPages = 1234;
        $expectedTitle = "php coding";
        $expectedYear = 2020;
        $expectedEan = "1002003004009";
        $book = new BookEntity(
            $expectedUserMock,
            $expectedAuthor,
            $expectedCountry,
            $expectedLanguage,
            $expectedPages,
            $expectedTitle,
            $expectedYear,
            $expectedEan
        );

        // fixed values
        $this->assertInstanceOf(UuidInterface::class, $book->getId());
        $this->assertEquals($expectedAuthor, $book->getAuthor());
        $this->assertEquals($expectedCountry, $book->getCountry());
        $this->assertEquals($expectedLanguage, $book->getLanguage());
        $this->assertEquals($expectedPages, $book->getPages());
        $this->assertEquals($expectedTitle, $book->getTitle());
        $this->assertEquals($expectedYear, $book->getYear());
        $this->assertEquals($expectedEan, $book->getEan());

        // settable data
        $book->setLocation($expectedUserMock);
        $this->assertEquals($expectedUserMock, $book->getLocation());
    }
}
