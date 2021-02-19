<?php declare(strict_types=1);

namespace SharedBookshelf\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use SharedBookshelf\Entities\Event;
use SharedBookshelf\Events\Event as EventInterface;

class EventRepository extends EntityRepository implements ObjectRepository
{
    // TODO: fix naming: entity !== eventInterface
    public function write(EventInterface $event): void
    {
        $eventEntity = new Event($event);

        $this->_em->persist($eventEntity);
        $this->_em->flush();
    }

    public function save(Event $event): void
    {
        $this->_em->persist($event);
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
