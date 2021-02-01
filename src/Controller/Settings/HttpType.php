<?php declare(strict_types=1);

namespace SharedBookshelf\Controller\Settings;

use RuntimeException;

class HttpType
{
    private string $type;
    private static string $post = 'post';
    private static string $get = 'get';

    public function __construct(string $type)
    {
        $type = strtolower($type);
        $this->ensureValidTypes($type);
        $this->type = $type;
    }

    public function asString(): string
    {
        return $this->type;
    }

    public function isGet(): bool
    {
        return $this->type === self::$get;
    }

    public function isPost(): bool
    {
        return $this->type === self::$post;
    }

    private function ensureValidTypes(string $type): void
    {
        if (!in_array($type, [self::$get, self::$post])) {
            throw new RuntimeException("Invalid type '$type'");
        }
    }
}
