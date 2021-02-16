<?php declare(strict_types=1);

namespace SharedBookshelf\Controller\FormValidators;

use Awurth\SlimValidation\Validator as FormValidator;
use Doctrine\ORM\EntityRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as V;

class SignupFormValidator
{
    private bool $isValidated = false;
    private array $errors = [];

    private string $username = '';
    private string $password = '';
    private string $email = '';

    private FormValidator $formValidator;
    private EntityRepository $userRepository;

    public function __construct(FormValidator $formValidator, EntityRepository $userRepository)
    {
        $this->formValidator = $formValidator;
        $this->userRepository = $userRepository;
    }

    /**
     * Validator issues
     * @psalm-suppress UndefinedMagicMethod
     * @psalm-suppress MixedMethodCall
     */
    public function validate(Request $request): self
    {
        $this->isValidated = false;
        $formData = $request->getParsedBody();

        // set validator rules
        $this->formValidator->validate($request, [
            'username' => V::length(4, 32)->alnum('_')->noWhitespace(),
            'password' => V::length(4, 32)->alnum('_'),
            'email' => V::notBlank()->email(),
        ]);

        // set internal form values
        $this->username = (string)($formData['username'] ?? '');
        $this->password = (string)($formData['password'] ?? '');
        $this->email = (string)($formData['email'] ?? '');

        // custom validation TODO: clone validator and move captcha into there (maybe doable with custom message)
        $formErrors = $this->formValidator->getErrors();
        $captchaSession = (string)($_SESSION['captchaCode'] ?? '');
        $captchaForm = strtolower((string)($formData['captchaCode'] ?? 'RandomNotMatching'));
        if ($captchaForm != $captchaSession) {
            $formErrors['captchaCode'] = 'The captcha does not match';
        }

        // validate existing user only after captcha is solved (disable db check)
        if (empty($formErrors)) {
            if ($this->userRepository->exist($this->username)) {
                $formErrors['username'][] = 'The username already exist, please choose another one';
            }
        }

        $this->errors = $formErrors;
        $this->isValidated = true;

        return $this;
    }

    public function hasErrors(): bool
    {
        if (empty($this->errors)) {
            return false;
        }
        return true;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
