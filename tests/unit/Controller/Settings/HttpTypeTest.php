<?php declare(strict_types=1);

namespace SharedBookshelf\Controller\Settings;

use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @covers \SharedBookshelf\Controller\Settings\HttpType
 */
final class HttpTypeTest extends TestCase
{
    /**
     * @dataProvider getValidTypes
     */
    public function testCanRetrieveData(string $type): void
    {
        $subject = new HttpType($type);
        $this->assertEquals($type, $subject->asString());
    }

    public function testCanAskForPostType(): void
    {
        $subject = new HttpType('post');
        $this->assertTrue($subject->isPost());
        $this->assertFalse($subject->isGet());
    }

    public function testCanAskForGetType(): void
    {
        $subject = new HttpType('get');
        $this->assertTrue($subject->isGet());
        $this->assertFalse($subject->isPost());
    }

    public function testCanNotBuildForUnknownType(): void
    {
        $type = 'unknown';
        $this->expectExceptionObject(new RuntimeException("Invalid type '$type'"));
        new HttpType($type);
    }

    public function getValidTypes(): array
    {
        return [
            ['get'],
            ['post'],
        ];
    }
}
