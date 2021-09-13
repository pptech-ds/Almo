<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Hospital;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class HospitalFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        
        $hospital = new Hospital;
        $hospital->setName('Hôpital de la Croix Simon');
        $hospital->setAddress('125, rue d’Avron');
        $hospital->setCity('Paris');
        $hospital->setZipcode('75020');
        $manager->persist($hospital);
        $this->addReference('hospital', $hospital);

        $manager->flush();
    }
}