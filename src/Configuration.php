<?php declare(strict_types=1);

namespace SharedBookshelf;

class Configuration
{
    private string $basePath;
    private string $dataPath;
    private int $debugLevel;
    private string $templates;
    private string $cache;
    private string $errorLog;

    public function __construct(FsWrapper $fsWrapper, File $configFile)
    {
        // open $configFile
        $this->basePath = $fsWrapper->realpath(__DIR__ . '/../') . '/';
        $this->dataPath = $this->basePath . 'data/';
        $this->templates = $this->basePath . 'templates/';
        $this->cache = $this->basePath . 'cache/';
        $this->errorLog = $this->basePath . 'logs/error.log';
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

    public function getErrorLog(): string
    {
        return $this->errorLog;
    }

    public function getEnvironment(): Environment
    {
        return new Environment('unit_test');
    }

    public function getCachePath(): string
    {
        return $this->cache;
    }
}
