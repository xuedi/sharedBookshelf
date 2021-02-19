<?php declare(strict_types=1);

namespace SharedBookshelf\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use SharedBookshelf\Entities\User;

class EventRepository extends EntityRepository implements ObjectRepository
{
    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
