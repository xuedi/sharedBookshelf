<?php declare(strict_types=1);

namespace SharedBookshelf;

use RuntimeException;
use SharedBookshelf\Entities\EventEntity;
use SharedBookshelf\Events\DummyEvent;
use SharedBookshelf\Events\Event;
use SharedBookshelf\Events\BookReceivedEvent;
use SharedBookshelf\Events\BookRequestEvent;
use SharedBookshelf\Events\BookHandoverEvent;
use SharedBookshelf\Events\LoginEvent;
use SharedBookshelf\Repositories\EventRepository;

class EventStore
{
    private EventRepository $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function append(Event $event): void
    {
        $eventEntity = new EventEntity(
            $event->getType(),
            $event->getPayload()
        );

        $this->eventRepository->save($eventEntity);
    }

    public function loadAllByType(EventType $type): array
    {
        $return = [];
        $eventEntities = $this->eventRepository->findByType($type);
        foreach ($eventEntities as $eventEntity) {
            $return[] = $this->hydrate($eventEntity);
        }
        return $return;
    }

    public function loadAll(): array
    {
        $return = [];
        $eventEntities = $this->eventRepository->findAll();
        foreach ($eventEntities as $eventEntity) {
            $return[] = $this->hydrate($eventEntity);
        }
        return $return;
    }

    private function hydrate(EventEntity $eventEntity): Event
    {
        $type = $eventEntity->getType()->asString();
        $parameter = [
            $eventEntity->getPayload(),
            $eventEntity->getCreated(),
            $eventEntity->getId()
        ];

        switch ($type) {
            case EventType::DUMMY:
                return DummyEvent::fromPayload(...$parameter);
            case EventType::LOGIN:
                return LoginEvent::fromPayload(...$parameter);
            case EventType::BOOK_REQUEST:
                return BookRequestEvent::fromPayload(...$parameter);
            case EventType::BOOK_HANDOVER:
                return BookHandoverEvent::fromPayload(...$parameter);
            case EventType::BOOK_RECEIVED:
                return BookReceivedEvent::fromPayload(...$parameter);
        }

        throw new RuntimeException("Could not hydrate the event: '$type'");
    }
}
