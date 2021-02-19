<?php declare(strict_types=1);

namespace SharedBookshelf\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SharedBookshelf\Crypto;
use SharedBookshelf\Entities\User;

/**
 * @codeCoverageIgnore
 */
class UserDataLoader extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $crypto = new Crypto();
        $data = $this->getDataProvider();
        foreach ($data as list($username, $password, $email)) {
            $user = new User(
                $username,
                $crypto->buildPasswordHash($password),
                $email
            );
            $manager->persist($user);
            $this->addReference('USER_' . md5($username), $user);
        }
        $manager->flush();
    }

    private function getDataProvider(): array
    {
        return [
            ['admin', 'admin', 'admin@email.com'],
            ['userA', '1234', 'user.a@email.com'],
            ['userB', '1234', 'user.b@email.com'],
            ['userC', '1234', 'user.c@email.com'],
            ['userD', '1234', 'user.d@email.com'],
            ['userE', '1234', 'user.e@email.com'],
        ];
    }
}
