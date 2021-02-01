<?php declare(strict_types=1);

namespace SharedBookshelf\Controller\Settings;

use PHPUnit\Framework\TestCase;

/**
 * @covers \SharedBookshelf\Controller\Settings\Path
 */
final class PathTest extends TestCase
{
    public function testCanRetrieveData(): void
    {
        $expected = '/test/';
        $subject = new Path($expected);
        $this->assertEquals($expected, $subject->asString());
    }
}
