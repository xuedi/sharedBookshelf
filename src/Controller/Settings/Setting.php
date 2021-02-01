<?php declare(strict_types=1);

namespace SharedBookshelf\Controller\Settings;

class Setting
{
    private Path $path;
    private Method $method;
    private HttpType $type;

    public function __construct(string $path, string $method, string $type)
    {
        $this->path = new Path($path);
        $this->method = new Method($method);
        $this->type = new HttpType($type);
    }

    public function getPath(): Path
    {
        return $this->path;
    }

    public function getMethod(): Method
    {
        return $this->method;
    }

    public function getType(): HttpType
    {
        return $this->type;
    }
}
