<?php declare(strict_types=1);

namespace SharedBookshelf\Controller;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface as Response;
use SharedBookshelf\Configuration;
use SharedBookshelf\Controller\Settings\Collection as ControllerSettings;
use Twig\Environment as Twig;

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
    use ControllerTrait;

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
        $this->assertRenderedTemplate('home.twig');

        $actual = $this->subject->index($this->requestMock, $this->responseMock);

        $this->assertInstanceOf(Response::class, $actual);
    }
}
