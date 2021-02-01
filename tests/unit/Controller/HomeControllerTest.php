<?php declare(strict_types=1);

namespace SharedBookshelf\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\StreamInterface;
use SharedBookshelf\Configuration;
use SharedBookshelf\Controller\Settings\Collection as ControllerSettings;
use Twig\Environment as Twig;
use Twig\TemplateWrapper;

/**
 * @covers \SharedBookshelf\Controller\HomeController
 * @uses   \SharedBookshelf\Controller\Settings\Collection
 * @uses   \SharedBookshelf\Controller\Settings\HttpType
 * @uses   \SharedBookshelf\Controller\Settings\HttpType
 * @uses   \SharedBookshelf\Controller\Settings\Method
 * @uses   \SharedBookshelf\Controller\Settings\Path
 * @uses   \SharedBookshelf\Controller\Settings\Setting
 */
final class HomeControllerTest extends TestCase
{
    private MockObject|Twig $twigMock;
    private MockObject|Configuration $configMock;
    private HomeController $subject;

    public function setUp(): void
    {
        $this->twigMock = $this->getMockBuilder(Twig::class)->disableOriginalConstructor()->getMock();
        $this->configMock = $this->createMock(Configuration::class);

        $this->subject = new HomeController(
            $this->twigMock,
            $this->configMock
        );
    }

    public function testCanGetSettingsCollection(): void
    {
        $this->assertInstanceOf(ControllerSettings::class, $this->subject->getSettings());
    }

    public function testCanRenderResponse(): void
    {
        $expectedTemplate = 'home.twig';
        $expectedContent = 'HomeController::index';




        $requestMock = $this->createMock(Request::class);

        $StreamInterfaceMock = $this->createMock(StreamInterface::class);
        $StreamInterfaceMock
            ->expects($this->once())
            ->method('write');

        $responseMock = $this->createMock(Response::class);
        $responseMock
            ->expects($this->once())
            ->method('getBody')
            ->with()
            ->willReturn($StreamInterfaceMock);

        $templateWrapperMock = $this->createMock(TemplateWrapper::class);
        $templateWrapperMock
            ->expects($this->once())
            ->method('render')
            ->with([])
            ->willReturn($expectedContent);

        $this->twigMock
            ->expects($this->once())
            ->method('load')
            ->with($expectedTemplate)
            ->willReturn($templateWrapperMock);




        $actual = $this->subject->index($requestMock, $responseMock);

        $this->assertInstanceOf(Response::class, $actual);
    }
}
