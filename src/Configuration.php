<?php declare(strict_types=1);

namespace SharedBookshelf;

use SharedBookshelf\Exceptions\FsWrapperException;

class Configuration
{
    private string $basePath;
    private string $dataPath;
    private int $debugLevel;

    public function __construct(FsWrapper $fsWrapper)
    {
        $this->basePath = $fsWrapper->realpath(__DIR__ . '/../') . '/';
        $this->dataPath = $this->basePath . 'data/';
        $this->debugLevel = 2;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function getDataPath(): string
    {
        return $this->dataPath;
    }

    public function getDebugLevel(): int
    {
        return $this->debugLevel;
    }
}
