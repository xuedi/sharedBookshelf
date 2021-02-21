<?php declare(strict_types=1);

namespace SharedBookshelf\Fixtures;

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\UuidInterface;
use ReflectionObject;
use SharedBookshelf\Entities\EventEntity;
use SharedBookshelf\Events\LoginEvent;
use SharedBookshelf\IpAddress;

/**
 * @codeCoverageIgnore
 */
class EventDataLoader extends AbstractFixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserDataLoader::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $data = $this->getEventList();
        foreach ($data as list($event, $age)) {
            $eventEntity = new EventEntity(
                $event->getType(),
                $event->getPayload()
            );
            $this->ageReflectionInjection($eventEntity, $age);
            $manager->persist($eventEntity);
        }
        $manager->flush();
    }

    private function getEventList(): array
    {
        return [
            [LoginEvent::fromParameters($this->getUserId('admin'), IpAddress::generate()), 'now -354 days'],
            [LoginEvent::fromParameters($this->getUserId('admin'), IpAddress::generate()), 'now -184 days'],
            [LoginEvent::fromParameters($this->getUserId('admin'), IpAddress::generate()), 'now -66 days'],
            [LoginEvent::fromParameters($this->getUserId('admin'), IpAddress::generate()), 'now -34 days'],
            [LoginEvent::fromParameters($this->getUserId('admin'), IpAddress::generate()), 'now -21 days'],
            [LoginEvent::fromParameters($this->getUserId('admin'), IpAddress::generate()), 'now -10 days'],
            [LoginEvent::fromParameters($this->getUserId('admin'), IpAddress::generate()), 'now -2 days'],
            [LoginEvent::fromParameters($this->getUserId('userA'), IpAddress::generate()), 'now -50 days'],
            [LoginEvent::fromParameters($this->getUserId('userA'), IpAddress::generate()), 'now -10 days'],
            [LoginEvent::fromParameters($this->getUserId('userA'), IpAddress::generate()), 'now -4 days'],
            [LoginEvent::fromParameters($this->getUserId('userB'), IpAddress::generate()), 'now -1242 days'],
            [LoginEvent::fromParameters($this->getUserId('userB'), IpAddress::generate()), 'now -1177 days'],
            [LoginEvent::fromParameters($this->getUserId('userC'), IpAddress::generate()), 'now -1 days'],
        ];
    }

    private function getUserId(string $username): UuidInterface
    {
        return $this->getReference('USER_' . md5($username))->getId();
    }

    private function ageReflectionInjection(EventEntity $book, string $age): EventEntity
    {
        $newDateTime = new DateTime($age);

        $refObject = new ReflectionObject($book);
        $refProperty = $refObject->getProperty('created');
        $refProperty->setAccessible(true);
        $refProperty->setValue($book, $newDateTime);

        return $book;
    }
}
