<?php declare(strict_types=1);

namespace SharedBookshelf;

use RuntimeException;

class Environment
{
    private string $env;
    private static string $production = 'production';
    private static string $staging = 'staging';
    private static string $unitTest = 'unit_test';

    public function __construct(string $env)
    {
        $env = strtolower($env);
        $this->ensureValidTypes($env);
        $this->env = $env;
    }

    public function asString(): string
    {
        return $this->env;
    }

    public function isProduction(): bool
    {
        return $this->env === self::$production;
    }

    public function isStaging(): bool
    {
        return $this->env === self::$staging;
    }

    public function isUnitTest(): bool
    {
        return $this->env === self::$unitTest;
    }

    private function ensureValidTypes(string $type): void
    {
        if (!in_array($type, [self::$production, self::$staging, self::$unitTest])) {
            throw new RuntimeException("Invalid environment '$type'");
        }
    }
}
