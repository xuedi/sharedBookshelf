<?php declare(strict_types=1);

namespace SharedBookshelf;

use DateTime;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use SharedBookshelf\Entities\HandoverEntity;
use SharedBookshelf\Repositories\BookRepository;
use SharedBookshelf\Repositories\HandoverRepository;
use SharedBookshelf\Repositories\UserRepository;

class Handover
{
    private UserRepository $userRepo;
    private BookRepository $bookRepo;
    private HandoverRepository $handoverRepo;

    public function __construct(UserRepository $userRepository, BookRepository $bookRepository, HandoverRepository $handoverRepository)
    {
        $this->userRepo = $userRepository;
        $this->bookRepo = $bookRepository;
        $this->handoverRepo = $handoverRepository;
    }

    public function bookRequest(UuidInterface $bookId, UuidInterface $userId, DateTime $time): void
    {
        // cant request a book that you have already
        $book = $this->bookRepo->findOneById($bookId);
        if ($book->getLocation()->getId()->equals($userId)) {
            throw new RuntimeException('cant request a book that you have already');
        }

        // cant request a book that you already requested
        $user = $this->userRepo->findOneById($userId);
        if ($this->handoverRepo->hasOpenRequest($book, $user)) {
            throw new RuntimeException('cant request a book that you already requested');
        }

        // add a new request
        $handover = new HandoverEntity($book, $user, $time);
        $this->handoverRepo->save($handover);
    }

    public function startBookHandover(UuidInterface $bookId, UuidInterface $userId, UuidInterface $recipientId, DateTime $time): void
    {
        // cant start a handover if i dont have the book
        $book = $this->bookRepo->findOneById($bookId);
        if (!$book->getLocation()->getId()->equals($userId)) {
            throw new RuntimeException('cant start a handover if i dont have the book');
        }

        // cant start a handover if there is any handover of this book in progress
        $user = $this->userRepo->findOneById($userId);
        if ($this->handoverRepo->hasOpenHandover($book, $user)) {
            throw new RuntimeException('cant start a handover if there is any handover of this book in progress');
        }

        // cant start a handover if the person did not request this book
        $recipient = $this->userRepo->findOneById($recipientId);
        if (!$this->handoverRepo->hasOpenRequest($book, $recipient)) {
            throw new RuntimeException('cant start a handover if the person did not request this book');
        }

        // set the book as handed over
        $handover = $this->handoverRepo->findMyOpenRequest($book, $recipient);
        $handover->setBookSendAt($time);
        $handover->setBookSendBy($user);
        $this->handoverRepo->save($handover);
    }

    public function bookReceived(UuidInterface $bookId, UuidInterface $userId, DateTime $time): void
    {
        // cant receive a book that i have already
        $book = $this->bookRepo->findOneById($bookId);
        if ($book->getLocation()->getId()->equals($userId)) {
            throw new RuntimeException('cant receive a book that i have already');
        }

        // cant receive a book if there is no handover in progress
        $bookSendBy = $this->userRepo->findOneById($book->getLocation()->getId());
        if (!$this->handoverRepo->hasOpenHandover($book, $bookSendBy)) {
            throw new RuntimeException('cant receive a book if there is no handover in progress');
        }

        // set the book as received
        $user = $this->userRepo->findOneById($userId);
        $handover = $this->handoverRepo->findMyOpenRequest($book, $user);
        $handover->setBookReceivedAt($time);
        $handover->setBookSendBy($user);
        $this->handoverRepo->save($handover);

        // update books location
        $book->setLocation($user);
        $this->bookRepo->save($book);
    }
}
