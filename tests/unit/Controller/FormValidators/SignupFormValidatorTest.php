<?php declare(strict_types=1);

namespace SharedBookshelf\Controller\FormValidators;

use Awurth\SlimValidation\Validator as AwurthValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SharedBookshelf\Repositories\UserRepository;
use Slim\Psr7\Factory\ServerRequestFactory;

/**
 * @covers \SharedBookshelf\Controller\FormValidators\SignupFormValidator
 */
final class SignupFormValidatorTest extends TestCase
{
    private AwurthValidator $awurthValidator;
    private ServerRequestFactory $requestFactory;
    private SignupFormValidator $subject;
    private MockObject|UserRepository $repoMock;

    public function setUp(): void
    {
        $this->awurthValidator = new AwurthValidator();
        $this->requestFactory = new ServerRequestFactory();
        $this->repoMock = $this->createMock(UserRepository::class);

        $this->subject = new SignupFormValidator(
            $this->awurthValidator,
            $this->repoMock
        );
    }

    public function testCanValidateForm(): void
    {
        $_SESSION['captchaCode'] = '1234';

        $request = $this->requestFactory->createServerRequest('POST', '/path')->withParsedBody([
            'username' => 'testUser',
            'password' => 'testPass1234',
            'email' => 'test@mail.com',
            'captchaCode' => '1234',
        ]);

        $this->repoMock
            ->expects($this->once())
            ->method('exist')
            ->with('testUser')
            ->willReturn(false);

        $validatedForm = $this->subject->validate($request);
        $formErrorsExpected = [];

        $this->assertFalse($validatedForm->hasErrors());
        $this->assertEquals($formErrorsExpected, $validatedForm->getErrors());
        $this->assertEquals('testUser', $validatedForm->getUsername());
        $this->assertEquals('testPass1234', $validatedForm->getPassword());
        $this->assertEquals('test@mail.com', $validatedForm->getEmail());
    }

    public function testCanCatchAlreadyExistingUser(): void
    {
        $_SESSION['captchaCode'] = '1234';

        $request = $this->requestFactory->createServerRequest('POST', '/path')->withParsedBody([
            'username' => 'testUser',
            'password' => 'testPass1234',
            'email' => 'test@mail.com',
            'captchaCode' => '1234',
        ]);

        $this->repoMock
            ->expects($this->once())
            ->method('exist')
            ->with('testUser')
            ->willReturn(true);

        $validatedForm = $this->subject->validate($request);
        $formErrorsExpected = [
            'username' => [
                'The username already exist, please choose another one'
            ]
        ];

        $this->assertTrue($validatedForm->hasErrors());
        $this->assertEquals($formErrorsExpected, $validatedForm->getErrors());
    }

    public function testCanCatchCaptchaMissMatch(): void
    {
        $_SESSION['captchaCode'] = 'XXXXX';

        $request = $this->requestFactory->createServerRequest('POST', '/path')->withParsedBody([
            'username' => 'testUser',
            'password' => 'testPass1234',
            'email' => 'test@mail.com',
            'captchaCode' => '1234',
        ]);

        $formErrors = $this->subject->validate($request)->getErrors();
        $formErrorsExpected = [
            'captchaCode' => 'The captcha does not match'
        ];

        $this->assertEquals($formErrorsExpected, $formErrors);
    }
}
