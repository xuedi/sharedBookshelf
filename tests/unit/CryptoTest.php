<?php declare(strict_types=1);

namespace SharedBookshelf;

use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @covers \SharedBookshelf\Crypto
 */
final class CryptoTest extends TestCase
{
    public function testCanRetrieveData(): void
    {
        $subject = new Crypto();

        $actual = $subject->buildPasswordHash('password');
        $expectedLength = 60;

        $this->assertEquals($expectedLength, strlen($actual));
    }
}
