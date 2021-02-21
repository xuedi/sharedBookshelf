<?php declare(strict_types=1);

namespace SharedBookshelf\Entities;

use ArrayIterator;
use IteratorAggregate;

class EventEntityCollection implements IteratorAggregate
{
    /**
     * @psalm-var list<EventEntity>
     */
    private array $eventEntities = [];

    public function add(EventEntity $eventEntity): void
    {
        $this->eventEntities[] = $eventEntity;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->eventEntities);
    }
}
