<?php declare(strict_types=1);

namespace SharedBookshelf\Events\Handler;

use RuntimeException;
use SharedBookshelf\Entities\HandoverEntity;
use SharedBookshelf\Events\Event;
use SharedBookshelf\Events\HandoverRequestEvent;
use SharedBookshelf\EventType;
use SharedBookshelf\Repositories\BookRepository;
use SharedBookshelf\Repositories\HandoverRepository;
use SharedBookshelf\Repositories\UserRepository;

class HandoverRequestEventHandler implements EventHandler
{
    private UserRepository $userRepository;
    private BookRepository $bookRepository;
    private HandoverRepository $handoverRepository;

    public function __construct(UserRepository $userRepository, BookRepository $bookRepository, HandoverRepository $handoverRepository)
    {
        $this->userRepository = $userRepository;
        $this->bookRepository = $bookRepository;
        $this->handoverRepository = $handoverRepository;
    }

    public function getType(): EventType
    {
        return EventType::fromString('handover_request');
    }

    public function handle(Event $event): void
    {
        $event = $this->ensureType($event);
        $book = $this->bookRepository->findOneById($event->getBookId());
        $requestedBy = $this->userRepository->findOneById($event->getRequestedBy());
        if ($this->handoverRepository->hasRequestedHandover($book, $requestedBy)) {
            return;
        }

        $handover = new HandoverEntity(
            $book,
            $requestedBy,
            $this->userRepository->findOneById($event->getRequestedFrom()),
            $event->getCreated()
        );

        $this->handoverRepository->save($handover);
    }

    private function ensureType(Event $event): HandoverRequestEvent
    {
        if (!$event instanceof HandoverRequestEvent) {
            throw new RuntimeException('Got wrong type');
        }
        return $event;
    }
}
