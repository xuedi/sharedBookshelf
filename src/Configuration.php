<?php declare(strict_types=1);

namespace SharedBookshelf;

class Configuration
{
    private string $basePath;
    private string $dataPath;
    private int $debugLevel;
    private string $templates;
    private string $cache;

    public function __construct(FsWrapper $fsWrapper)
    {
        $this->basePath = $fsWrapper->realpath(__DIR__ . '/../') . '/';
        $this->dataPath = $this->basePath . 'data/';
        $this->templates = $this->basePath . 'templates/';
        $this->cache = $this->basePath . 'cache/';
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

    public function getTemplatePath(): string
    {
        return $this->templates;
    }

    public function getCachePath(): string
    {
        return $this->cache;
    }
}
