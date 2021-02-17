<?php declare(strict_types=1);

namespace SharedBookshelf;

use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SharedBookshelf\Entities\User;

class Auth
{
    private static string $nobody = 'guest';
    private ?UuidInterface $userId;
    private string $username;
    private EntityRepository $userRepository;

    public function __construct(EntityRepository $userRepository)
    {
        $this->userId = null;
        $this->username = self::$nobody;

        $this->attemptRestore();
        $this->userRepository = $userRepository;
    }

    public function getId(): ?UuidInterface
    {
        return $this->userId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function verify(string $username, string $password): bool
    {
        if ($this->userId !== null) {
            return true;
        }

        /** @var User $user */
        $user = $this->userRepository->findOneBy([
            'username' => $username
        ]);

        if (!$user) {
            return false;
        }

        if(password_verify($password, $user->getPasswordHash())) {
            $this->login($user->getId(), $user->getUsername());
            return true;
        }

        return false;
    }

    public function login(UuidInterface $userId, string $username): void
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->store();
    }

    public function logout()
    {
        $this->userId = null;
        $this->username = self::$nobody;
        $this->store();
    }

    private function attemptRestore(): void
    {
        if (!isset($_SESSION['auth_user_id'])) {
            return;
        }
        if (!isset($_SESSION['auth_username'])) {
            return;
        }
        $uuidString = (string)$_SESSION['auth_user_id'];
        if (Uuid::isValid($uuidString)) {
            $this->userId = Uuid::fromString($uuidString);
            $this->username = (string)$_SESSION['auth_username'];
        }
    }

    private function store(): void
    {
        $_SESSION['auth_username'] = $this->username;
        $_SESSION['auth_user_id'] = $this->userId ? $this->userId->toString() : null;
    }
}
