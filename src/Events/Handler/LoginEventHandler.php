<?php declare(strict_types=1);

namespace SharedBookshelf\Events\Handler;

use RuntimeException;
use SharedBookshelf\Events\Event;
use SharedBookshelf\Events\LoginEvent;
use SharedBookshelf\EventType;
use SharedBookshelf\Repositories\UserRepository;

class LoginEventHandler implements EventHandler
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getType(): EventType
    {
        return EventType::fromString('login');
    }

    public function handle(Event $event): void
    {
        $event = $this->ensureType($event);
        $this->userRepository->updateLastLogin(
            $event->getUserId(),
            $event->getCreated()
        );
    }

    private function ensureType(Event $event): LoginEvent
    {
        if (!$event instanceof LoginEvent) {
            throw new RuntimeException('Got wrong type');
        }
        return $event;
    }
}
