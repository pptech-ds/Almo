<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Speciality;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class SpecialityFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $specialities = ['Acupuncture', 'Réflexologie', 'Kinésithérapie', 'Ostéopathie', 'Shiatsu', 'Chiropractie', 'Hypnose', 'Sophrologie', 'Nutrithérapie', 'Naturopathie'];

        $faker = Factory::create('fr_FR'); 
        
        for($i=0; $i<count($specialities); $i++) {
            $speciality = new Speciality;
            $speciality->setName($specialities[$i]);
            $speciality->setDescription($faker->realText($maxNbChars = 200, $indexSize = 2));
            $manager->persist($speciality);

            // Creating references to get them in ArtitFixtures
            $this->addReference('speciality_' . $i, $speciality);
        }
        $manager->flush();
    }
}