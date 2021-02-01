<?php declare(strict_types=1);

namespace SharedBookshelf\Controller\Settings;

use PHPUnit\Framework\TestCase;

/**
 * @covers \SharedBookshelf\Controller\Settings\Method
 */
final class MethodTest extends TestCase
{
    public function testCanRetrieveData(): void
    {
        $expected = 'index';
        $subject = new Method($expected);
        $this->assertEquals($expected, $subject->asString());
    }
}
