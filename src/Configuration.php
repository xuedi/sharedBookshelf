<?php declare(strict_types=1);

namespace SharedBookshelf;

use SharedBookshelf\Exceptions\FsWrapperException;

class Configuration
{
    private string $basePath;
    private string $dataPath;

    public function __construct(File $config)
    {
        $data = (array)parse_ini_file($config->asString());

        $this->basePath = $this->createBasePath();
        $this->dataPath = $this->createDataPath($data);
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function getDataPath(): string
    {
        return $this->dataPath;
    }

    private function createBasePath(): string
    {
        return realpath(__DIR__ . '/../') . '/';
    }

    private function createDataPath(array $data): string
    {
        if (isset($data['dataPath'])) {
            $path = (string)$data['dataPath'];
            if (substr($path, 0, 1) != '/') {
                $path = $this->basePath . $path; // relative
            }
        } else {
            $path = $this->basePath . 'data/';
        }
        return realpath($path) . "/";
    }
}
