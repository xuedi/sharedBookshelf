<?php declare(strict_types=1);

namespace SharedBookshelf;

use PHPUnit\Framework\TestCase;

/**
 * @covers \SharedBookshelf\DatabaseConfig
 */
final class DatabaseConfigTest extends TestCase
{
    private DatabaseConfig $subject;
    private string $expectedUser = 'user';
    private string $expectedPass = 'pass';
    private string $expectedHost = 'name';
    private string $expectedName = 'host';

    public function setUp(): void
    {
        $this->subject = new DatabaseConfig(
            $this->expectedUser,
            $this->expectedPass,
            $this->expectedName,
            $this->expectedHost
        );
    }

    /**
     * @dataProvider getExpectedData
     * @param string $expectedData
     * @param string $getterMethod
     */
    public function testCanRetrieveData(string $expectedData, string $getterMethod): void
    {
        $this->assertEquals($expectedData, $this->subject->{$getterMethod}());
    }

    public function getExpectedData(): array
    {
        return [
            [$this->expectedUser, 'getUsername'],
            [$this->expectedPass, 'getPassword'],
            [$this->expectedName, 'getDbName'],
            [$this->expectedHost, 'getHost'],
        ];
    }
}
