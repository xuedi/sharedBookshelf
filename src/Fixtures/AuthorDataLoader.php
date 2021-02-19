<?php declare(strict_types=1);

namespace SharedBookshelf\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use SharedBookshelf\Entities\Author;

/**
 * @codeCoverageIgnore
 */
class AuthorDataLoader extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $data = $this->getDataProvider();
        foreach ($data as $name) {
            $author = new Author($name);
            $manager->persist($author);
            $this->addReference('AUTHOR_'.md5($name), $author);
        }
        $manager->flush();
    }

    private function getDataProvider(): array
    {
        return [
            'Chinua Achebe',
            'Hans Christian Andersen',
            'Dante Alighieri',
            'Unknown',
            'Jane Austen',
            'Honoré de Balzac',
            'Samuel Beckett',
            'Giovanni Boccaccio',
            'Jorge Luis Borges',
            'Emily Brontë',
            'Albert Camus',
            'Paul Celan',
            'Louis-Ferdinand Céline',
            'Miguel de Cervantes',
            'Geoffrey Chaucer',
            'Anton Chekhov',
            'Joseph Conrad',
            'Charles Dickens',
            'Denis Diderot',
            'Alfred Döblin',
            'Fyodor Dostoevsky',
            'George Eliot',
            'Ralph Ellison',
            'Euripides',
            'William Faulkner',
            'Gustave Flaubert',
            'Federico García Lorca',
            'Gabriel García Márquez',
            'Johann Wolfgang von Goethe',
            'Nikolai Gogol',
            'Günter Grass',
            'João Guimarães Rosa',
            'Knut Hamsun',
            'Ernest Hemingway',
            'Homer',
            'Henrik Ibsen',
            'James Joyce',
            'Franz Kafka',
            'Kālidāsa',
            'Yasunari Kawabata',
            'Nikos Kazantzakis',
            'D. H. Lawrence',
            'Halldór Laxness',
            'Giacomo Leopardi',
            'Doris Lessing',
            'Astrid Lindgren',
            'Lu Xun',
            'Naguib Mahfouz',
            'Thomas Mann',
            'Herman Melville',
            'Michel de Montaigne',
            'Elsa Morante',
            'Toni Morrison',
            'Murasaki Shikibu',
            'Robert Musil',
            'Vladimir Nabokov',
            'George Orwell',
            'Ovid',
            'Fernando Pessoa',
            'Edgar Allan Poe',
            'Marcel Proust',
            'François Rabelais',
            'Juan Rulfo',
            'Rumi',
            'Salman Rushdie',
            'Saadi',
            'Tayeb Salih',
            'José Saramago',
            'William Shakespeare',
            'Sophocles',
            'Stendhal',
            'Laurence Sterne',
            'Italo Svevo',
            'Jonathan Swift',
            'Leo Tolstoy',
            'Mark Twain',
            'Valmiki',
            'Virgil',
            'Vyasa',
            'Walt Whitman',
            'Virginia Woolf',
            'Marguerite Yourcenar',
        ];
    }
}
