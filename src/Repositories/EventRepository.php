<?php declare(strict_types=1);

namespace SharedBookshelf\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use SharedBookshelf\Entities\Event as EventEntity;
use SharedBookshelf\Events\Event as EventInterface;
use SharedBookshelf\Events\LoginEvent;
use SharedBookshelf\EventType;

class EventRepository extends EntityRepository implements ObjectRepository
{
    // TODO: fix naming: entity !== eventInterface
    public function write(EventInterface $event): void
    {
        $eventEntity = new EventEntity($event);

        $this->_em->persist($eventEntity);
        $this->_em->flush();
    }

    /**
     * @return array<LoginEvent>
     */
    public function byType(EventType $type): array
    {
        /** @var EventEntity $event */

        $queryBuilder = $this->_em->createQueryBuilder();
        $query = $queryBuilder
            ->select('e')
            ->from(EventEntity::class, 'e')
            ->where('e.type = :eventType')
            ->orderBy('e.created', 'ASC')
            ->setParameter('eventType', $type->asString())
            ->getQuery();

        $events = [];
        $results = $query->getResult();
        foreach ($results as $event) { // TODO: hydrate via doctrine
            $events[] = LoginEvent::fromPayload(
                $event->getPayload(),
                $event->getCreated()
            );
        }

        return $events;
    }

    public function save(EventEntity $event): void
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
