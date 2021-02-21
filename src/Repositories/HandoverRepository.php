<?php declare(strict_types=1);

namespace SharedBookshelf\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use SharedBookshelf\Entities\BookEntity;
use SharedBookshelf\Entities\HandoverEntity;
use SharedBookshelf\Entities\UserEntity;

class HandoverRepository extends EntityRepository implements ObjectRepository
{
    public function hasOpenRequest(BookEntity $book, UserEntity $user): bool
    {
        $count = $this->count(['book' => $book, 'requestedBy' => $user, 'bookReceivedAt' => null]);
        if ($count > 0) {
            return true;
        }
        return false;
    }

    public function hasOpenHandover(BookEntity $book, UserEntity $user): bool
    {
        $count = $this->count(['book' => $book, 'bookSendBy' => $user, 'bookReceivedAt' => null]);
        if ($count > 0) {
            return true;
        }
        return false;
    }

    public function findMyOpenRequest(BookEntity $book, UserEntity $requestedBy): HandoverEntity
    {
        $handover = $this->findOneBy(['book' => $book, 'requestedBy' => $requestedBy, 'bookReceivedAt' => null]);
        if ($handover === null) {
            throw new RuntimeException("Could not find the handover");
        }
        if (!$handover instanceof HandoverEntity) {
            throw new RuntimeException('Got wrong type');
        }
        return $handover;
    }

    public function findOneById(UuidInterface $id): HandoverEntity
    {
        $handover = $this->findOneBy(['id' => $id]);
        if ($handover === null) {
            throw new RuntimeException("Could not find the handover '{$id->toString()}'");
        }
        if (!$handover instanceof HandoverEntity) {
            throw new RuntimeException('Got wrong type');
        }
        return $handover;
    }

    public function save(HandoverEntity $handover): void
    {
        $this->_em->persist($handover);
        $this->_em->flush();
    }
}
