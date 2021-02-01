<?php declare(strict_types=1);

namespace SharedBookshelf\Controller\Settings;

class Method
{
    private string $method;

    public function __construct(string $method)
    {
        // ensure valid method
        $this->method = $method;
    }

    public function asString(): string
    {
        return $this->method;
    }
}
