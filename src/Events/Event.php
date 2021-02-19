<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

use SharedBookshelf\EventType;

interface Event
{
    public function asPayload(): array;

    public function getType(): EventType;

    public static function fromPayload(array $payload): self;
}
