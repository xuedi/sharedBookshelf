<?php declare(strict_types=1);

namespace SharedBookshelf\Controller\Settings;

use PHPUnit\Framework\TestCase;

/**
 * @covers \SharedBookshelf\Controller\Settings\Collection
 * @uses   \SharedBookshelf\Controller\Settings\HttpType
 * @uses   \SharedBookshelf\Controller\Settings\Method
 * @uses   \SharedBookshelf\Controller\Settings\Path
 * @uses   \SharedBookshelf\Controller\Settings\Setting
 */
final class CollectionTest extends TestCase
{
    public function testCanRetrieveData(): void
    {
        $expected = new Setting('/', 'index', 'get');
        $subject = new Collection([$expected]);

        $this->assertEquals($expected, $subject->getIterator()->current());
    }
}
