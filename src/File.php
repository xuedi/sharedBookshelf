<?php declare(strict_types=1);

namespace SharedBookshelf;

use RuntimeException;

class File
{
    private string $path;
    private string $name;

    public function __construct(string $file)
    {
        $this->ensureFileExists($file);
        $this->ensureIsReadable($file);

        $this->path = rtrim(realpath(dirname($file)), '/') . '/';
        $this->name = basename($file);
    }

    public function asString(): string
    {
        return $this->path . $this->name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    private function ensureFileExists(string $file): void
    {
        if (!file_exists($file)) {
            throw new RuntimeException("The file '$file' does not exist.");
        }
    }

    private function ensureIsReadable(string $file): void
    {
        if (!is_readable($file)) {
            throw new RuntimeException("The file '$file' is not readable.");
        }
    }
}
