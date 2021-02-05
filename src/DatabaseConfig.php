<?php declare(strict_types=1);

namespace SharedBookshelf;

class DatabaseConfig
{
    private string $username;
    private string $password;
    private string $dbname;
    private string $host;

    public function __construct(string $user, string $pass, string $name, string $host)
    {
        $this->username = $user;
        $this->password = $pass;
        $this->dbname = $name;
        $this->host = $host;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDbname(): string
    {
        return $this->dbname;
    }

    public function getHost(): string
    {
        return $this->host;
    }

}
