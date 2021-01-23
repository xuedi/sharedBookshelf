<?php declare(strict_types=1);

namespace SharedBookshelf;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Slim\App;

/**
 * @covers \SharedBookshelf\File
 */
final class FileTest extends TestCase
{
    private File $subject;

    public function setUp(): void
    {
        $testFile = __DIR__ . '/fixtures/config.ini';
        $this->subject = new File($testFile);
    }

    public function testGetAsString(): void
    {
        $expected = __DIR__ . '/fixtures/config.ini';
        $this->assertEquals($expected, $this->subject->asString());
    }

    public function testGetName(): void
    {
        $expected = 'config.ini';
        $this->assertEquals($expected, $this->subject->getName());
    }

    public function testGetPath(): void
    {
        $expected = __DIR__ . '/fixtures/';
        $this->assertEquals($expected, $this->subject->getPath());
    }

    public function testCanNotOpenNonExistingFile(): void
    {
        $expected = '/tmp/NothingHere';
        $this->expectExceptionObject(new RuntimeException("The file '$expected' does not exist."));
        new File($expected);
    }

    public function testCanNotOpenWithBadPermissions(): void
    {
        $expected = '/etc/gshadow';
        $this->expectExceptionObject(new RuntimeException("The file '$expected' is not readable."));
        new File($expected);
    }
}
