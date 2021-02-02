<?php declare(strict_types=1);

namespace SharedBookshelf\Controller\FormValidators;

use Awurth\SlimValidation\Validator as AwurthValidator;
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;

/**
 * @covers \SharedBookshelf\Controller\FormValidators\SignupFormValidator
 */
final class SignupFormValidatorTest extends TestCase
{
    private AwurthValidator $awurthValidator;
    private ServerRequestFactory $requestFactory;
    private SignupFormValidator $subject;

    public function setUp(): void
    {
        $this->awurthValidator = new AwurthValidator();
        $this->requestFactory = new ServerRequestFactory();

        $this->subject = new SignupFormValidator(
            $this->awurthValidator
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

        $formErrors = $this->subject->validate($request);
        $formErrorsExpected = [];

        $this->assertEquals($formErrorsExpected, $formErrors);
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

        $formErrors = $this->subject->validate($request);
        $formErrorsExpected = [
            'captchaCode' => 'The captcha does not match'
        ];

        $this->assertEquals($formErrorsExpected, $formErrors);
    }
}
