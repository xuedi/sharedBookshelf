<?php declare(strict_types=1);

namespace SharedBookshelf;

use RuntimeException;

class Crypto
{
    private static int $cost = 12;

    public function buildPasswordHash(string $password): string
    {
        $seasonedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => self::$cost]);
        if (!$seasonedPassword) {
            throw new RuntimeException('Could not salt the password');
        }
        return $seasonedPassword;
    }
}
