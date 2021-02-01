<?php declare(strict_types=1);

namespace SharedBookshelf\Controller\Settings;

use PHPUnit\Framework\TestCase;

/**
 * @covers \SharedBookshelf\Controller\Settings\Setting
 * @uses   \SharedBookshelf\Controller\Settings\HttpType
 * @uses   \SharedBookshelf\Controller\Settings\Method
 * @uses   \SharedBookshelf\Controller\Settings\Path
 * @uses   \SharedBookshelf\Controller\Settings\Setting
 */
final class SettingTest extends TestCase
{
    public function testCanRetrieveData(): void
    {
        $expectedPath = '/home/';
        $expectedMethod = 'home';
        $expectedType = 'get';

        $subject = new Setting($expectedPath, $expectedMethod, $expectedType);

        $this->assertEquals($expectedPath, $subject->getPath()->asString());
        $this->assertEquals($expectedMethod, $subject->getMethod()->asString());
        $this->assertEquals($expectedType, $subject->getType()->asString());
    }
}
