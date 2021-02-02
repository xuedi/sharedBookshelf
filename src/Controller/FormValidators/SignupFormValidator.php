<?php declare(strict_types=1);

namespace SharedBookshelf\Controller\FormValidators;

use Awurth\SlimValidation\Validator as FormValidator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as V;

class SignupFormValidator
{
    private FormValidator $formValidator;

    public function __construct(FormValidator $formValidator)
    {
        $this->formValidator = $formValidator;
    }

    /**
     * Validator issues
     * @psalm-suppress UndefinedMagicMethod
     * @psalm-suppress MixedMethodCall
     */
    public function validate(Request $request): array
    {
        $formData = $request->getParsedBody();

        // set validator rules
        $this->formValidator->validate($request, [
            'username' => V::length(4, 32)->alnum('_')->noWhitespace(),
            'password' => V::length(4, 32)->alnum('_'),
            'email' => V::notBlank()->email(),
        ]);

        // custom validation TODO: clone validator and move captcha into there (maybe doable with custom message)
        $formErrors = $this->formValidator->getErrors();
        $captchaSession = (string)($_SESSION['captchaCode'] ?? '');
        $captchaForm = strtolower((string)($formData['captchaCode'] ?? 'RandomNotMatching'));
        if ($captchaForm != $captchaSession) {
            $formErrors['captchaCode'] = 'The captcha does not match';
        }

        return $formErrors;
    }
}
