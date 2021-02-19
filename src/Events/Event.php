<?php declare(strict_types=1);

namespace SharedBookshelf\Events;

interface Event
{
    public function asPayload(): array;
}
