<?php declare(strict_types=1);

namespace SharedBookshelf;

use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @covers \SharedBookshelf\EventType
 */
final class EventTypeTest extends TestCase
{
    public function testCanHandleUnknownType(): void
    {
        $this->expectExceptionObject(new RuntimeException("Invalid type: 'unknown'"));
        EventType::fromString('unknown');
    }

    /**
     * @dataProvider getValidTypes
     * @param string $expectedTypeString
     */
    public function testCanBeBuild(string $expectedTypeString): void
    {
        $subject = EventType::fromString($expectedTypeString);
        $this->assertInstanceOf(EventType::class, $subject);
        $this->assertEquals($expectedTypeString, $subject->asString());
    }

    public function getValidTypes(): array
    {
        return [
            ['dummy'],
            ['signup'],
            ['login'],
        ];
    }
}
