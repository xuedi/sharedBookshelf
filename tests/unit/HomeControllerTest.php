<?php declare(strict_types=1);

namespace SharedBookshelf;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\StreamInterface;
use SharedBookshelf\Controller\HomeController;

/**
 * @covers \SharedBookshelf\Controller\HomeController
 */
final class HomeControllerTest extends TestCase
{
    private HomeController $subject;

    public function setUp(): void
    {
        $this->subject = new HomeController();
    }

    public function testGetBeBuild(): void
    {
        $this->assertInstanceOf(HomeController::class, $this->subject);
    }

    public function testReturnResponse(): void
    {
        $request = $this->createMock(Request::class);

        $streamInterfaceMock = $this->createMock(StreamInterface::class);
        $streamInterfaceMock
            ->expects($this->once())
            ->method('write')
            ->with('HomeController::index');

        $response = $this->createMock(Response::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($streamInterfaceMock);

        $this->assertEquals($response, $this->subject->index($request, $response));
    }
}
