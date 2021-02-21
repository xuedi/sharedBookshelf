<?php declare(strict_types=1);

namespace SharedBookshelf\Events\Handler;

use SharedBookshelf\Events\Event;
use SharedBookshelf\EventType;

interface EventHandler
{
    public function getType(): EventType;

    public function handle(Event $event): void;
}
