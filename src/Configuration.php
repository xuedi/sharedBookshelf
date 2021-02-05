<?php declare(strict_types=1);

namespace SharedBookshelf;

class Configuration
{
    private int $debugLevel;
    private string $basePath;
    private string $dataPath;
    private string $templates;
    private string $cache;
    private string $errorLog;
    private DatabaseConfig $database;

    public function __construct(FsWrapper $fsWrapper, File $configFile)
    {
        $data = parse_ini_file($configFile->asString(), true);
        $this->basePath = $fsWrapper->realpath(__DIR__ . '/../') . '/';
        $this->dataPath = $this->basePath . 'data/';
        $this->templates = $this->basePath . 'templates/';
        $this->cache = $this->basePath . 'cache/';
        $this->errorLog = $this->basePath . 'logs/error.log';
        $this->debugLevel = 2;
        $this->database = new DatabaseConfig(
            (string)$data['database']['username'] ?? '',
            (string)$data['database']['password'] ?? '',
            (string)$data['database']['name'] ?? '',
            (string)$data['database']['host'] ?? '',
        );
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

    public function getDatabase(): DatabaseConfig
    {
        return $this->database;
    }
}
