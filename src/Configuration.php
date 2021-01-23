<?php declare(strict_types=1);

namespace SharedBookshelf;

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
        $path = $this->basePath . 'data/';
        if (isset($data['dataPath'])) {
            var_dump($data['dataPath']);
            $path = (string)$data['dataPath'];
            if (substr($path, 0, 1) != '/') {
                $path = $this->basePath . $path; // relative
            }
        }
        return realpath($path) . "/";
    }
}
