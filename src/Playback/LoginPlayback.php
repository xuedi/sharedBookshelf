<?php declare(strict_types=1);

namespace SharedBookshelf\Playback;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;
use SharedBookshelf\Entities\UserEntity;
use SharedBookshelf\Events\LoginEvent;
use SharedBookshelf\EventType;
use SharedBookshelf\Repositories\EventRepository;
use SharedBookshelf\Repositories\UserRepository;

class LoginPlayback
{
    private EntityRepository|UserRepository $userRepository;
    private EntityRepository|EventRepository $eventRepository;

    public function __construct(EntityRepository $eventRepository, EntityRepository $userRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->userRepository = $userRepository;
    }

    public function execute(): void
    {
        $lastLoginList = $this->getBasicUserLoginList();
        $lastLoginList = $this->updateLastLogin($lastLoginList);

        foreach ($lastLoginList as $userId => $lastLogin) {
            $this->userRepository->updateLastLogin(
                Uuid::fromString($userId),
                $lastLogin
            );
        }
    }

    private function updateLastLogin(array $lastLoginList): array
    {
        /** @var LoginEvent $loginEvent */

        $loginEvents = $this->eventRepository->byType(EventType::fromString('login'));
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
        /** @var UserEntity $user */

        $basicDateTime = new DateTime('1900-01-01 00:00:00');

        $lastLoginList = [];
        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $lastLoginList[$user->getId()->toString()] = $basicDateTime;
        }

        return $lastLoginList;
    }
}
