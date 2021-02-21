<?php declare(strict_types=1);

namespace SharedBookshelf\Events\Handler;

use RuntimeException;
use SharedBookshelf\Events\Event;
use SharedBookshelf\Events\HandoverStartedEvent;
use SharedBookshelf\EventType;
use SharedBookshelf\Repositories\BookRepository;
use SharedBookshelf\Repositories\HandoverRepository;
use SharedBookshelf\Repositories\UserRepository;

class HandoverStartedEventHandler implements EventHandler
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
        return EventType::fromString('handover_started');
    }

    public function handle(Event $event): void
    {
        $event = $this->ensureType($event);
        $book = $this->bookRepository->findOneById($event->getBookId());
        $requestedBy = $this->userRepository->findOneById($event->getHandoverTo());
        if (!$this->handoverRepository->hasRequestedHandover($book, $requestedBy)) {
            return;
        }
        $handover = $this->handoverRepository->findRequestedHandoverForBook($book);
        $handover->setBookSendAt($event->getCreated());

        $this->handoverRepository->save($handover);
    }

    private function ensureType(Event $event): HandoverStartedEvent
    {
        if (!$event instanceof HandoverStartedEvent) {
            throw new RuntimeException('Got wrong type');
        }
        return $event;
    }
}
