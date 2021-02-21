<?php declare(strict_types=1);

namespace SharedBookshelf\Playback;

use DateTime;
use Ramsey\Uuid\Uuid;
use SharedBookshelf\EventStore;
use SharedBookshelf\EventType;
use SharedBookshelf\Repositories\UserRepository;

class LoginPlayback
{
    private UserRepository $userRepository;
    private EventStore $eventStore;

    public function __construct(EventStore $eventStore, UserRepository $userRepository)
    {
        $this->eventStore = $eventStore;
        $this->userRepository = $userRepository;
    }

    public function execute(): void
    {
        $lastLoginList = $this->getBasicUserLoginList();
        $lastLoginList = $this->extractEventLogins($lastLoginList);
        foreach ($lastLoginList as $userId => $lastLogin) {
            $this->userRepository->updateLastLogin(
                Uuid::fromString($userId),
                $lastLogin
            );
        }
    }

    private function extractEventLogins(array $lastLoginList): array
    {
        $loginEvents = $this->eventStore->loadAllByType(EventType::fromString('login'));
        foreach ($loginEvents as $loginEvent) {
            $userId = $loginEvent->getUserId()->toString();
            $created = $loginEvent->getCreated();
            if ($created > $lastLoginList[$userId]) {
                $lastLoginList[$userId] = $created;
            }
        }
        return $lastLoginList;
    }

    private function getBasicUserLoginList(): array
    {
        $lastLoginList = [];
        $users = $this->userRepository->findAll();
        $basicDateTime = new DateTime('1900-01-01 00:00:00');
        foreach ($users as $user) {
            $lastLoginList[$user->getId()->toString()] = $basicDateTime;
        }
        return $lastLoginList;
    }
}
