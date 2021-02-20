<?php declare(strict_types=1);

namespace SharedBookshelf\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use SharedBookshelf\Entities\AuthorEntity;
use SharedBookshelf\Entities\CountryEntity;

/**
 * @codeCoverageIgnore
 */
class CountryDataLoader extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $data = $this->getDataProvider();
        foreach ($data as $name) {
            $country = new CountryEntity($name);
            $manager->persist($country);
            $this->addReference('COUNTRY_' . md5($name), $country);
        }
        $manager->flush();
    }

    private function getDataProvider(): array
    {
        return [
            'Achaemenid Empire',
            'Algeria',
            'Argentina',
            'Asia',
            'Austria',
            'Brazil',
            'China',
            'Colombia',
            'Czechoslovakia',
            'Denmark',
            'Egypt',
            'England',
            'France',
            'Germany',
            'Greece',
            'Iceland',
            'India',
            'Ireland',
            'Irish Free State',
            'Italy',
            'Japan',
            'Mexico',
            'Nigeria',
            'Norway',
            'Persian Empire',
            'Portugal',
            'Republic of Ireland',
            'Roman Empire',
            'Russia',
            'Saxe-Weimar',
            'Spain',
            'Sudan',
            'Sultanate of Rum',
            'Sumer and Akkadian Empire',
            'Sweden',
            'United Kingdom',
            'United States',
        ];
    }
}
