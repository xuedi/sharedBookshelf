<?php declare(strict_types=1);

namespace SharedBookshelf;

use SharedBookshelf\Exceptions\ConfigException;

class Configuration
{
    private string $basePath;
    private string $dataPath;

    public function __construct()
    {
        $this->basePath = realpath(__DIR__ . '/../') . '/';
        $data = parse_ini_file($this->basePath . 'config.ini');

        $this->setDataPath($data['dataPath'] ?? __DIR__ . '/../data');
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function getDataPath(): string
    {
        return $this->dataPath;
    }

    private function setDataPath(string $path)
    {
        $path = rtrim(realpath($path) . '/', '/');
        $this->dataPath = $path;
        $this->ensurePathExists($path);
        $this->ensurePathIsWritable($path);
    }

    private function ensurePathExists(string $path): void
    {
        if(!is_dir($path)) {
            throw new ConfigException("The Path '$path' does not exist.");
        }
    }

    private function ensurePathIsWritable(string $path): void
    {
        if(!is_writable($path)) {
            throw new ConfigException("The Path '$path' needs write permissions.");
        }
    }
}
