<?php declare(strict_types=1);

namespace SharedBookshelf\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use RuntimeException;
use SharedBookshelf\Entities\BookEntity;
use SharedBookshelf\Entities\HandoverEntity;
use SharedBookshelf\Entities\UserEntity;

class HandoverRepository extends EntityRepository implements ObjectRepository
{
    public function findRequestedHandoverForBook(BookEntity $book): HandoverEntity
    {
        $handover = $this->findOneBy(['book' => $book, 'bookReceivedAt' => null]);
        if ($handover === null) {
            throw new RuntimeException("No requested handover for bookid '{$book->getId()->toString()}' ");
        }
        if (!$handover instanceof HandoverEntity) {
            throw new RuntimeException('Got wrong type');
        }
        return $handover;
    }

    public function findStartedHandoverForBook(BookEntity $book): HandoverEntity
    {
        $handover = $this->findOneBy(['book' => $book, 'bookReceivedAt' => null]);
        if (!$handover instanceof HandoverEntity) {
            throw new RuntimeException('Got wrong type');
        }
        return $handover;
    }

    public function hasRequestedHandover(BookEntity $book, UserEntity $user): bool
    {
        $count = $this->count(['book' => $book, 'requestedBy' => $user, 'bookReceivedAt' => null]);
        if ($count == 0) {
            return false;
        }
        return true;
    }

    public function save(HandoverEntity $handover): void
    {
        $this->_em->persist($handover);
        $this->_em->flush();
    }
}
