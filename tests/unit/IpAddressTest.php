<?php declare(strict_types=1);

namespace SharedBookshelf;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface as Request;
use RuntimeException;

/**
 * @covers \SharedBookshelf\IpAddress
 */
final class IpAddressTest extends TestCase
{
    public function testCanGenerate(): void
    {
        $subject = IpAddress::generate();
        $this->assertInstanceOf(IpAddress::class, $subject);
        $this->assertEquals(3, substr_count($subject->asString(), '.'));
    }

    public function testCanBuildFromString(): void
    {
        $expected = '192.168.1.1';
        $subject = IpAddress::fromString($expected);
        $this->assertEquals($expected, $subject->asString());
    }

    public function testCanBuildFromRequest(): void
    {
        $expected = '192.168.1.1';

        $requestMock = $this->createMock(Request::class);
        $requestMock->expects($this->once())->method('getServerParams')->willReturn(['REMOTE_ADDR' => $expected]);

        $subject = IpAddress::fromRequest($requestMock);
        $this->assertEquals($expected, $subject->asString());
    }

    public function testCanCatchInvalidFormat(): void
    {
        $address = '192.168.1.X';
        $this->expectExceptionObject(new RuntimeException("Invalid IP address: '$address'"));
        IpAddress::fromString($address);
    }
}
