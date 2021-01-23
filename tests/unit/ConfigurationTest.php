<?php declare(strict_types=1);

namespace SharedBookshelf;

use PHPUnit\Framework\TestCase;

/**
 * @covers \SharedBookshelf\Configuration
 * @uses   \SharedBookshelf\File
 */
final class ConfigurationTest extends TestCase
{
    private Configuration $config;
    private Configuration $configDefault;

    public function setUp(): void
    {
        $this->config = new Configuration(new File(__DIR__ . '/fixtures/config.ini'));
        $this->configDefault = new Configuration(new File(__DIR__ . '/fixtures/configEmpty.ini'));
    }

    public function testGetBasePath(): void
    {
        $expected = realpath(__DIR__ . '/../../') . '/';
        $this->assertEquals($expected, $this->config->getBasePath());
    }

    public function testGetDataPath(): void
    {
        $expected = realpath(__DIR__ . '/../../') . '/data/';
        $this->assertEquals($expected, $this->config->getDataPath());
    }

    public function testCanWithDefaults(): void
    {
        $expected = realpath(__DIR__ . '/../../') . '/data/';
        $this->assertEquals($expected, $this->configDefault->getDataPath());
    }
}
