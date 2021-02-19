<?php declare(strict_types=1);

namespace SharedBookshelf\Fixtures;

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use ReflectionObject;
use SharedBookshelf\Entities\Event;
use SharedBookshelf\Entities\User;
use SharedBookshelf\Events\LoginEvent;
use SharedBookshelf\EventType;
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
        $data = $this->getDataProvider();
        foreach ($data as list($username, $days)) {

            /** @var User $userEntity */
            $userEntity = $this->getReference('USER_' . md5($username));
            $loginEvent = LoginEvent::fromParameters($userEntity, IpAddress::generate());
            $newDateTime = new DateTime('now -' . (string)$days . ' days');

            $book = new Event($loginEvent);

            $this->reflectionInjection($book, $newDateTime);

            $manager->persist($book);
        }
        $manager->flush();
    }

    private function getDataProvider(): array
    {
        return [
            ['admin', 354],
            ['admin', 184],
            ['admin', 66],
            ['admin', 34],
            ['admin', 21],
            ['admin', 10],
            ['admin', 2],
            ['userA', 50],
            ['userA', 10],
            ['userA', 4],
            ['userB', 1242],
            ['userB', 1177],
            ['userC', 1],
        ];
    }

    private function reflectionInjection(Event $book, DateTime $newDateTime): Event
    {
        $refObject = new ReflectionObject($book);
        $refProperty = $refObject->getProperty('created');
        $refProperty->setAccessible(true);
        $refProperty->setValue($book, $newDateTime);

        return $book;
    }
}
