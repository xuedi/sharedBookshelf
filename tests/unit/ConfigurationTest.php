<?php declare(strict_types=1);

namespace SharedBookshelf;

use PHPUnit\Framework\TestCase;

/**
 * @covers \SharedBookshelf\Configuration
 * @uses   \SharedBookshelf\FsWrapper
 * @uses   \SharedBookshelf\Environment
 */
final class ConfigurationTest extends TestCase
{
    private Configuration $subject;

    public function setUp(): void
    {
        $configFileMock = $this->createMock(File::class);

        $fsWrapperMock = $this->createMock(FsWrapper::class);
        $fsWrapperMock->expects($this->once())->method('realpath')->willReturn('/testPath');

        $this->subject = new Configuration($fsWrapperMock, $configFileMock);
    }

    public function testGetBasePath(): void
    {
        $expected = '/testPath/';
        $this->assertEquals($expected, $this->subject->getBasePath());
    }

    public function testGetErrorLog(): void
    {
        $expected = '/testPath/logs/error.log';
        $this->assertEquals($expected, $this->subject->getErrorLog());
    }

    public function testGetDataPath(): void
    {
        $expected = '/testPath/data/';
        $this->assertEquals($expected, $this->subject->getDataPath());
    }

    public function testGetTemplatePath(): void
    {
        $expected = '/testPath/templates/';
        $this->assertEquals($expected, $this->subject->getTemplatePath());
    }

    public function testGetCachePath(): void
    {
        $expected = '/testPath/cache/';
        $this->assertEquals($expected, $this->subject->getCachePath());
    }

    public function testGetEnvironment(): void
    {
        $expected = 'unit_test';
        $this->assertEquals($expected, $this->subject->getEnvironment()->asString());
    }

    public function testCanDebugLevel(): void
    {
        $expected = 2;
        $this->assertEquals($expected, $this->subject->getDebugLevel());
    }
}
