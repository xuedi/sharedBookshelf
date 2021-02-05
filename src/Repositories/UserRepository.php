<?php declare(strict_types=1);

namespace SharedBookshelf\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;

class UserRepository extends EntityRepository implements ObjectRepository
{
    public function findByUsername(string $username)
    {
        $this->_em->createQuery('SELECT * FROM AppBundle\Entity\User WHERE usernamename LIKE "'.$username.'"');
    }
}
