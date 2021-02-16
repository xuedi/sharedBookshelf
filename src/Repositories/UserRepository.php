<?php declare(strict_types=1);

namespace SharedBookshelf\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ObjectRepository;
use SharedBookshelf\Entities\User;

class UserRepository extends EntityRepository implements ObjectRepository
{
    /**
     * @codeCoverageIgnore Not going to touch UnitOfWork mocking nightmare
     */
    public function exist(string $username): bool
    {
        if ($this->findOneBy(['username' => $username])) {
            return true;
        }
        return false;
    }

    public function findByUsername(string $username): Query
    {
        $query = $this->_em->createQuery('SELECT * FROM AppBundle\Entity\User WHERE username LIKE :username');
        $query->setParameter('username', $username);

        return $query;
    }

    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
