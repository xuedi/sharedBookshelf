<?php declare(strict_types=1);

namespace SharedBookshelf;

class Configuration
{
    private bool $debugLevel;
    private string $basePath;
    private string $dataPath;
    private string $templates;
    private string $cache;
    private string $errorLog;
    private DatabaseConfig $database;
    private Environment $environment;

    public function __construct(FsWrapper $fsWrapper, File $configFile)
    {
        $data = parse_ini_file($configFile->asString(), true);
        $this->basePath = $fsWrapper->realpath(__DIR__ . '/../') . '/';
        $this->dataPath = $this->basePath . 'data/';
        $this->templates = $this->basePath . 'templates/';
        $this->cache = $this->basePath . 'cache/';
        $this->errorLog = $this->basePath . 'logs/error.log';
        $this->debugLevel = (bool)($data['general']['debug'] ?? false); // TODO: actually look at value ^_^
        $this->environment = new Environment((string)($data['general']['environment'] ?? 'production'));
        $this->database = new DatabaseConfig(
            (string)($data['database']['username'] ?? ''),
            (string)($data['database']['password'] ?? ''),
            (string)($data['database']['name'] ?? ''),
            (string)($data['database']['host'] ?? ''),
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

    public function isDebug(): bool
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
        return $this->environment;
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
