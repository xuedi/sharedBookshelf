<?php declare(strict_types=1);

namespace SharedBookshelf\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use SharedBookshelf\Entities\EventEntityCollection;
use SharedBookshelf\Entities\EventEntity;
use SharedBookshelf\EventType;

class EventRepository extends EntityRepository implements ObjectRepository
{
    public function save(EventEntity $event): void
    {
        $this->_em->persist($event);
        $this->_em->flush();
    }

    public function findByType(EventType $type): EventEntityCollection
    {
        $collection = new EventEntityCollection();
        $eventEntities = $this->findBy(['type' => $type->asString()]);
        foreach ($eventEntities as $eventEntity) {
            $collection->add($eventEntity);
        }
        return $collection;
    }

    public function findAll(): EventEntityCollection
    {
        $collection = new EventEntityCollection();
        $eventEntities = $this->findBy([], ['created' => 'ASC']);
        foreach ($eventEntities as $eventEntity) {
            $collection->add($eventEntity);
        }
        return $collection;
    }
}
