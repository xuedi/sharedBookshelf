<?php declare(strict_types=1);

namespace SharedBookshelf\Fixtures;

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use ReflectionObject;
use SharedBookshelf\Entities\EventEntity;
use SharedBookshelf\Events\BookReceivedEvent;
use SharedBookshelf\Events\BookRequestEvent;
use SharedBookshelf\Events\BookHandoverEvent;
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
        $bookB = $this->getBook('Chinua Achebe', 'Things Fall Apart')->getId();

        return [
            // dummy login data
            [LoginEvent::fromParameters($admin, IpAddress::generate()), 'now -354 days'],
            [LoginEvent::fromParameters($admin, IpAddress::generate()), 'now -184 days'],
            [LoginEvent::fromParameters($admin, IpAddress::generate()), 'now -66 days'],
            [LoginEvent::fromParameters($admin, IpAddress::generate()), 'now -34 days'],

            // clean handover
            [LoginEvent::fromParameters($userC, IpAddress::generate()), 'now -20 days'],
            [BookRequestEvent::fromParameters($bookA, $userC), 'now -20 days'],
            [LoginEvent::fromParameters($admin, IpAddress::generate()), 'now -19 days'],
            [BookHandoverEvent::fromParameters($bookA, $admin, $userC), 'now -19 days'],
            [LoginEvent::fromParameters($userC, IpAddress::generate()), 'now -18 days'],
            [BookReceivedEvent::fromParameters($bookA, $userC), 'now -18 days'],

            // popular book in multiple ques
            [BookRequestEvent::fromParameters($bookB, $userA), 'now -3 days'],
            [BookRequestEvent::fromParameters($bookB, $userB), 'now -3 days'],
            [BookRequestEvent::fromParameters($bookB, $userC), 'now -2 days'],
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
