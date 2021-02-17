<?php declare(strict_types=1);

namespace SharedBookshelf\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use SharedBookshelf\Entities\Book;

class BookDataLoader implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $book = new Book(
            'author',
            'bookname',
            '12345678901234'
        );

        $manager->persist($book);
        $manager->flush();
    }
}