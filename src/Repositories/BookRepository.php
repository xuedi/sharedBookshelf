<?php declare(strict_types=1);

namespace SharedBookshelf\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use SharedBookshelf\Entities\BookEntity;

class BookRepository extends EntityRepository implements ObjectRepository
{
    public function findOneById(UuidInterface $id): BookEntity
    {
        $book = $this->findOneBy(['id' => $id]);
        if ($book === null) {
            throw new RuntimeException("Could not find the book '{$id->toString()}'");
        }
        if (!$book instanceof BookEntity) {
            throw new RuntimeException('Got wrong type');
        }
        return $book;
    }

    public function save(BookEntity $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
