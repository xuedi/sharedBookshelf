<?php declare(strict_types=1);

namespace SharedBookshelf;

use Psr\Http\Message\ServerRequestInterface as Request;
use RuntimeException;

class IpAddress
{
    private string $address;

    public static function generate(): self
    {
        return new self(long2ip(rand(0, 4294967295)));
    }

    public static function fromString(string $address): self
    {
        return new self($address);
    }

    public static function fromRequest(Request $request): self
    {
        $ip = (string)$request->getServerParams()['REMOTE_ADDR'];
        return new self($ip);
    }

    private function __construct(string $address)
    {
        $this->ensureIsValid($address);
        $this->address = $address;
    }

    public function asString(): string
    {
        return $this->address;
    }

    private function ensureIsValid(string $address): void
    {
        if (!filter_var($address, FILTER_VALIDATE_IP)) {
            throw new RuntimeException("Invalid IP address: '$address'");
        }
    }
}
