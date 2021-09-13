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
        // $specialities = ['Acupuncture', 'Réflexologie', 'Kinésithérapie', 'Ostéopathie', 'Shiatsu', 'Chiropractie', 'Hypnose', 'Sophrologie', 'Nutrithérapie', 'Naturopathie'];
        // $specialityProfessionNames = ['Acupuncteur', 'Réflexologue', 'Kinésithérapeute', 'Ostéopathe', 'Praticien Shiatsu', 'Chiropracteur', 'Hypnothérapeute', 'Sophrologue', 'Nutrithérapeute', 'Naturopathe'];
        
        $faker = Factory::create('fr_FR'); 
        
        $speciality = new Speciality;
        $speciality->setName('Acupuncture');
        $speciality->setProfessionName('Acupuncteur');
        $speciality->setDescription($faker->realText($maxNbChars = 2000, $indexSize = 2));
        $speciality->setImage('img/demo'.rand(1,18).'.jpg');
        $manager->persist($speciality);

        // Creating references to get them in ArtitFixtures
        $this->addReference('speciality', $speciality);

        $manager->flush();
    }
}