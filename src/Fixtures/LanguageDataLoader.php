<?php declare(strict_types=1);

namespace SharedBookshelf\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use SharedBookshelf\Entities\LanguageEntity;

/**
 * @codeCoverageIgnore
 */
class LanguageDataLoader extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $data = $this->getDataProvider();
        foreach ($data as $name) {
            $language = new LanguageEntity($name);
            $manager->persist($language);
            $this->addReference('LANGUAGE_' . md5($name), $language);
        }
        $manager->flush();
    }

    private function getDataProvider(): array
    {
        return [
            'English',
            'Danish',
            'Italian',
            'Akkadian',
            'Hebrew',
            'Arabic',
            'Old Norse',
            'French',
            'Spanish',
            'German',
            'Russian',
            'Greek',
            'Portuguese',
            'Norwegian',
            'Sanskrit',
            'Japanese',
            'Icelandic',
            'Swedish',
            'Chinese',
            'Classical Latin',
            'Persian',
        ];
    }
}
