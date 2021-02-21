<?php declare(strict_types=1);

namespace SharedBookshelf\Events\Handler;

use RuntimeException;
use SharedBookshelf\Events\Event;
use SharedBookshelf\Events\HandoverConfirmedEvent;
use SharedBookshelf\EventType;
use SharedBookshelf\Repositories\BookRepository;
use SharedBookshelf\Repositories\HandoverRepository;
use SharedBookshelf\Repositories\UserRepository;

class HandoverConfirmedEventHandler implements EventHandler
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
        return EventType::fromString('handover_confirmed');
    }

    public function handle(Event $event): void
    {
        $event = $this->ensureType($event);
        $book = $this->bookRepository->findOneById($event->getBookId());
        $user = $this->userRepository->findOneById($event->getUserId());
        if (!$this->handoverRepository->hasRequestedHandover($book, $user)) {
            return;
        }

        // finish handover
        $handover = $this->handoverRepository->findStartedHandoverForBook($book);
        if ($handover->getBookSendAt() == null) {
            throw new RuntimeException("Cant have received a book, that was never send");
        }
        $handover->setBookReceivedAt($event->getCreated());
        $this->handoverRepository->save($handover);

        // update books location
        $book->setLocation($user);
        $this->bookRepository->save($book);
    }

    private function ensureType(Event $event): HandoverConfirmedEvent
    {
        if (!$event instanceof HandoverConfirmedEvent) {
            throw new RuntimeException('Got wrong type');
        }
        return $event;
    }
}
