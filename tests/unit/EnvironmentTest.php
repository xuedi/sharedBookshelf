<?php declare(strict_types=1);

namespace SharedBookshelf;

use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @covers \SharedBookshelf\Environment
 */
final class EnvironmentTest extends TestCase
{
    /**
     * @dataProvider getValidTypes
     */
    public function testCanRetrieveData(string $type): void
    {
        $subject = new Environment($type);
        $this->assertEquals($type, $subject->asString());
    }

    public function testCanAskForProduction(): void
    {
        $subject = new Environment('production');
        $this->assertTrue($subject->isProduction());
        $this->assertFalse($subject->isStaging());
        $this->assertFalse($subject->isUnitTest());
        $this->assertFalse($subject->isDevelopment());
    }

    public function testCanAskForStaging(): void
    {
        $subject = new Environment('staging');
        $this->assertTrue($subject->isStaging());
        $this->assertFalse($subject->isProduction());
        $this->assertFalse($subject->isUnitTest());
        $this->assertFalse($subject->isDevelopment());
    }

    public function testCanAskForUnitTest(): void
    {
        $subject = new Environment('unit_test');
        $this->assertTrue($subject->isUnitTest());
        $this->assertFalse($subject->isProduction());
        $this->assertFalse($subject->isStaging());
        $this->assertFalse($subject->isDevelopment());
    }

    public function testCanAskForDevelopment(): void
    {
        $subject = new Environment('development');
        $this->assertTrue($subject->isDevelopment());
        $this->assertFalse($subject->isUnitTest());
        $this->assertFalse($subject->isProduction());
        $this->assertFalse($subject->isStaging());
    }

    public function testCanNotBuildForUnknownType(): void
    {
        $expected = 'unknown';
        $this->expectExceptionObject(new RuntimeException("Invalid environment '$expected'"));
        new Environment($expected);
    }

    public function getValidTypes(): array
    {
        return [
            ['production'],
            ['staging'],
            ['unit_test'],
        ];
    }
}
