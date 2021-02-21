<?php declare(strict_types=1);

namespace SharedBookshelf\Events\Handler;

use RuntimeException;
use SharedBookshelf\Events\BookHandoverEvent;
use SharedBookshelf\Events\Event;
use SharedBookshelf\EventType;
use SharedBookshelf\Handover;

class BookHandoverEventHandler implements EventHandler
{
    private Handover $handover;

    public function __construct(Handover $handover)
    {
        $this->handover = $handover;
    }

    public function getType(): EventType
    {
        return EventType::fromString('book_handover');
    }

    public function handle(Event $event): void
    {
        $event = $this->ensureType($event);
        $this->handover->startBookHandover(
            $event->getBookId(),
            $event->getFromId(),
            $event->getToId(),
            $event->getCreated()
        );

        // TODO: update fancy projections, send mails etc
    }

    private function ensureType(Event $event): BookHandoverEvent
    {
        if (!$event instanceof BookHandoverEvent) {
            throw new RuntimeException('Got wrong type');
        }
        return $event;
    }
}
