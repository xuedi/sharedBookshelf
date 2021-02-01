<?php declare(strict_types=1);

namespace SharedBookshelf\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\StreamInterface;
use SharedBookshelf\Configuration;
use Twig\Environment as Twig;
use Twig\TemplateWrapper;

trait ControllerTrait
{
    private MockObject|Twig $twigMock;
    private MockObject|Configuration $configMock;
    private MockObject|Request $requestMock;
    private MockObject|Response $responseMock;

    public function assertRenderedTemplate(string $expectedTemplate): void
    {
        $this->requestMock = $this->createMock(Request::class);
        $this->responseMock = $this->createMock(Response::class);


        $StreamInterfaceMock = $this->createMock(StreamInterface::class);
        $StreamInterfaceMock
            ->expects($this->once())
            ->method('write');

        $this->responseMock
            ->expects($this->once())
            ->method('getBody')
            ->with()
            ->willReturn($StreamInterfaceMock);

        $templateWrapperMock = $this->createMock(TemplateWrapper::class);
        $templateWrapperMock
            ->expects($this->once())
            ->method('render')
            ->with([])
            ->willReturn('controllerContent');

        $this->twigMock
            ->expects($this->once())
            ->method('load')
            ->with($expectedTemplate)
            ->willReturn($templateWrapperMock);
    }
}
