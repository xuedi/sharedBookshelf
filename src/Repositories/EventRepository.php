<?php declare(strict_types=1);

namespace SharedBookshelf\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use SharedBookshelf\Entities\User;
use SharedBookshelf\Events\DummyEvent;
use SharedBookshelf\Events\Event as EventInterface;
use SharedBookshelf\Events\LoginEvent;

class EventRepository extends EntityRepository implements ObjectRepository
{
    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /*
    public function build(): EventInterface
    {
        $type = $this->type->asString();
        $payload = (array)json_decode($this->payload, true);

        switch ($type) {
            case 'dummy':
                return DummyEvent::fromPayload($payload);
            case 'login':
                return LoginEvent::fromPayload($payload);
        }
        throw new RuntimeException("Could build the Event from payload: '$type'");
    }
    */
}
