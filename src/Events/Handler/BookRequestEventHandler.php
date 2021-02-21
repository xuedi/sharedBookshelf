<?php declare(strict_types=1);

namespace SharedBookshelf\Events\Handler;

use RuntimeException;
use SharedBookshelf\Events\BookRequestEvent;
use SharedBookshelf\Events\Event;
use SharedBookshelf\EventType;
use SharedBookshelf\Handover;

class BookRequestEventHandler implements EventHandler
{
    private Handover $handover;

    public function __construct(Handover $handover)
    {
        $this->handover = $handover;
    }

    public function getType(): EventType
    {
        return EventType::fromString('book_request');
    }

    public function handle(Event $event): void
    {
        $event = $this->ensureType($event);

        $this->handover->bookRequest(
            $event->getBookId(),
            $event->getUserId(),
            $event->getCreated()
        );

        // TODO: update fancy projections, send mails etc
    }

    private function ensureType(Event $event): BookRequestEvent
    {
        if (!$event instanceof BookRequestEvent) {
            throw new RuntimeException('Got wrong type');
        }
        return $event;
    }
}
