<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Entities\User;

/**
 * @covers \SharedBookshelf\Entities\Book
 * @uses   \SharedBookshelf\Entities\Author
 * @uses   \SharedBookshelf\Entities\Language
 * @uses   \SharedBookshelf\Entities\Country
 */
final class BookTest extends TestCase
{
    public function testCanRetrieveData(): void
    {
        $expectedAuthor = new Author("xuedi");
        $expectedCountry = new Country("England");
        $expectedLanguage = new Language("English");
        $expectedPages = 1234;
        $expectedTitle = "php coding";
        $expectedYear = 2020;
        $expectedEan = "1002003004009";
        $user = new Book(
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
