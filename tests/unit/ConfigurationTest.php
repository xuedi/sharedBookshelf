<?php declare(strict_types=1);

namespace SharedBookshelf;

use PHPUnit\Framework\TestCase;

/**
 * @covers \SharedBookshelf\Configuration
 * @uses   \SharedBookshelf\FsWrapper
 * @uses   \SharedBookshelf\Environment
 * @uses   \SharedBookshelf\DatabaseConfig
 */
final class ConfigurationTest extends TestCase
{
    private Configuration $subject;

    public function setUp(): void
    {
        $configFileMock = $this->createMock(File::class);
        $configFileMock->expects($this->once())->method('asString')->willReturn(__DIR__ . '/fixtures/config.ini');

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
        $expected = true;
        $this->assertEquals($expected, $this->subject->isDebug());
    }

    public function testCanGetDatabaseConfig(): void
    {
        $expected = DatabaseConfig::class;
        $this->assertInstanceOf($expected, $this->subject->getDatabase());
    }
}
