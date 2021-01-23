<?php declare(strict_types=1);

namespace SharedBookshelf;

use PHPUnit\Framework\TestCase;

/**
 * @covers \SharedBookshelf\Configuration
 * @uses   \SharedBookshelf\File
 */
final class ConfigurationTest extends TestCase
{
    private Configuration $configNormal;
    private Configuration $configDefault;
    private Configuration $configAbsolut;

    public function setUp(): void
    {
        $this->configNormal = new Configuration(new File(__DIR__ . '/fixtures/config.ini'));
        $this->configDefault = new Configuration(new File(__DIR__ . '/fixtures/configEmpty.ini'));
        $this->configAbsolut = new Configuration(new File(__DIR__ . '/fixtures/configAbsolute.ini'));
    }

    public function testGetBasePath(): void
    {
        $expected = realpath(__DIR__ . '/../../') . '/';
        $this->assertEquals($expected, $this->configNormal->getBasePath());
    }

    public function testGetDataPath(): void
    {
        $expected = realpath(__DIR__ . '/../../') . '/data/';
        $this->assertEquals($expected, $this->configNormal->getDataPath());
    }

    public function testCanWithDefaults(): void
    {
        $expected = realpath(__DIR__ . '/../../') . '/data/';
        $this->assertEquals($expected, $this->configDefault->getDataPath());
    }

    public function testCanDealWithAbsolutePaths(): void
    {
        $expected = '/tmp/';
        $this->assertEquals($expected, $this->configAbsolut->getDataPath());
    }
}
