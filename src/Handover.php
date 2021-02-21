<?php declare(strict_types=1);

namespace SharedBookshelf;

use Ramsey\Uuid\UuidInterface;
use RuntimeException;

class Handover
{
    public function bookRequest(UuidInterface $bookId, UuidInterface $userId): void
    {
        // cant if already requested
        // cant if i have the book already
    }

    public function startBookHandover(UuidInterface $bookId, UuidInterface $userId, UuidInterface $recipientId): void
    {
        // cant start a handover if there is any handover in progress
        // cant start a handover if the person did not request this book
    }

    public function bookReceived(UuidInterface $bookId, UuidInterface $userId): void
    {
        // cant receive a book if there is not handover in progress
    }
}
