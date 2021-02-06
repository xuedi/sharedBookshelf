<?php declare(strict_types=1);

namespace SharedBookshelf\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ObjectRepository;
use SharedBookshelf\Entities\User;

class UserRepository extends EntityRepository implements ObjectRepository
{
    public function findByUsername(string $username): Query
    {
        return $this->_em->createQuery('SELECT * FROM AppBundle\Entity\User WHERE usernamename LIKE "' . $username . '"');
    }

    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
