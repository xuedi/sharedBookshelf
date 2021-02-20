<?php declare(strict_types=1);

namespace SharedBookshelf;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment as Twig;

/**
 * @covers \SharedBookshelf\Factory
 * @uses   \SharedBookshelf\Configuration
 * @uses   \SharedBookshelf\Controller\HomeController
 * @uses   \SharedBookshelf\Controller\PrivacyController
 * @uses   \SharedBookshelf\Controller\TermsController
 * @uses   \SharedBookshelf\Controller\ImagesController
 * @uses   \SharedBookshelf\Controller\FormValidators\SignupFormValidator
 * @uses   \SharedBookshelf\Controller\Settings\Collection
 * @uses   \SharedBookshelf\Controller\Settings\HttpType
 * @uses   \SharedBookshelf\Controller\Settings\Method
 * @uses   \SharedBookshelf\Controller\Settings\Path
 * @uses   \SharedBookshelf\Controller\Settings\Setting
 * @uses   \SharedBookshelf\Environment
 * @uses   \SharedBookshelf\Entities\UserEntity
 * @uses   \SharedBookshelf\Auth
 */
final class FactoryTest extends TestCase
{
    private MockObject|File $configFileMock;
    private MockObject|Configuration $configurationMock;
    private MockObject|Framework $frameworkMock;
    private MockObject|Twig $twigMock;
    private Factory $subject;

    public function setUp(): void
    {
        $this->configFileMock = $this->createMock(File::class);
        $this->configurationMock = $this->createMock(Configuration::class);
        $this->frameworkMock = $this->createMock(Framework::class);
        $this->twigMock = $this->createMock(Twig::class);

        $this->subject = new FactoryStub(
            $this->configFileMock,
            $this->configurationMock,
            $this->twigMock,
            $this->frameworkMock
        );
    }

    public function testCanSetUp(): void
    {
        $this->frameworkMock->expects($this->atLeast(6))->method('registerController');
        $this->subject->setup();
    }

    public function testCanRun(): void
    {
        $this->frameworkMock->expects($this->once())->method('run');
        $this->subject->run();
    }
}
