<?php declare(strict_types=1);

namespace SharedBookshelf\Events\Handler;

use RuntimeException;
use SharedBookshelf\Events\BookReceivedEvent;
use SharedBookshelf\Events\Event;
use SharedBookshelf\EventType;
use SharedBookshelf\Handover;

class BookReceivedEventHandler implements EventHandler
{
    private Handover $handover;

    public function __construct(Handover $handover)
    {
        $this->handover = $handover;
    }

    public function getType(): EventType
    {
        return EventType::fromString('book_received');
    }

    public function handle(Event $event): void
    {
        $event = $this->ensureType($event);
        $this->handover->bookReceived(
            $event->getBookId(),
            $event->getUserId(),
            $event->getCreated(),
        );

        // TODO: update fancy projections, send mails etc
    }

    private function ensureType(Event $event): BookReceivedEvent
    {
        if (!$event instanceof BookReceivedEvent) {
            throw new RuntimeException('Got wrong type');
        }
        return $event;
    }
}
