<?php declare(strict_types=1);

namespace SharedBookshelf\Controller\Settings;

class Path
{
    private string $path;

    public function __construct(string $path)
    {
        // ensure valid path
        $this->path = $path;
    }

    public function asString(): string
    {
        return $this->path;
    }
}
