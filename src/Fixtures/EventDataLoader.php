<?php declare(strict_types=1);

namespace SharedBookshelf\Fixtures;

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use ReflectionObject;
use SharedBookshelf\Entities\EventEntity;
use SharedBookshelf\Events\HandoverConfirmedEvent;
use SharedBookshelf\Events\HandoverRequestEvent;
use SharedBookshelf\Events\HandoverStartedEvent;
use SharedBookshelf\Events\LoginEvent;
use SharedBookshelf\IpAddress;

/**
 * @codeCoverageIgnore
 */
class EventDataLoader extends AbstractFixture implements DependentFixtureInterface
{
    use TypedReferenceGetter;

    public function getDependencies()
    {
        return [
            UserDataLoader::class,
            BookDataLoader::class,
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
        $admin = $this->getUser('admin')->getId();
        $userA = $this->getUser('userA')->getId();
        $userB = $this->getUser('userB')->getId();
        $userC = $this->getUser('userC')->getId();

        $bookA = $this->getBook('Alfred Döblin', 'Berlin Alexanderplatz')->getId();

        return [
            [LoginEvent::fromParameters($admin, IpAddress::generate()), 'now -354 days'],
            [LoginEvent::fromParameters($admin, IpAddress::generate()), 'now -184 days'],
            [LoginEvent::fromParameters($admin, IpAddress::generate()), 'now -66 days'],
            [LoginEvent::fromParameters($admin, IpAddress::generate()), 'now -34 days'],
            [LoginEvent::fromParameters($admin, IpAddress::generate()), 'now -21 days'],
            [LoginEvent::fromParameters($admin, IpAddress::generate()), 'now -10 days'],
            [LoginEvent::fromParameters($admin, IpAddress::generate()), 'now -2 days'],
            [LoginEvent::fromParameters($userA, IpAddress::generate()), 'now -50 days'],
            [LoginEvent::fromParameters($userA, IpAddress::generate()), 'now -10 days'],
            [LoginEvent::fromParameters($userA, IpAddress::generate()), 'now -4 days'],
            [LoginEvent::fromParameters($userB, IpAddress::generate()), 'now -1242 days'],
            [LoginEvent::fromParameters($userB, IpAddress::generate()), 'now -1177 days'],
            [LoginEvent::fromParameters($userC, IpAddress::generate()), 'now -1 days'],

            // clean handover 1
            [HandoverRequestEvent::fromParameters($userC, $admin, $bookA), 'now -20 days'],
            [HandoverStartedEvent::fromParameters($admin, $userC, $bookA), 'now -19 days'],
            [HandoverConfirmedEvent::fromParameters($userC, $bookA), 'now -19 days'],

            // clean handover 2
            [HandoverRequestEvent::fromParameters($userB, $userC, $bookA), 'now -11 days'],
            [HandoverStartedEvent::fromParameters($userC, $userB, $bookA), 'now -8 days'],
            [HandoverConfirmedEvent::fromParameters($userB, $bookA), 'now -7 days'],

            // popular book in multiple ques
            [HandoverRequestEvent::fromParameters($userA, $userB, $bookA), 'now -3 days'],
            [HandoverRequestEvent::fromParameters($admin, $userB, $bookA), 'now -3 days'],
            [HandoverRequestEvent::fromParameters($userC, $userB, $bookA), 'now -2 days'],
        ];
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
