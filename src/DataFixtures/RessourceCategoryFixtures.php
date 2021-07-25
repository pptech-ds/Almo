<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\RessourceCategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RessourceCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $ressourceCategories = ['Article', 'Document'];

        $faker = Factory::create('fr_FR'); 

        for($i=0; $i<count($ressourceCategories); $i++) {
            $ressourceCategory = new RessourceCategory;
            $ressourceCategory->setName($ressourceCategories[$i]);
            $ressourceCategory->setDescription($faker->realText($maxNbChars = 200, $indexSize = 2));
            $manager->persist($ressourceCategory);

            // Creating references to get them in ArtitFixtures
            $this->addReference('ressourceCategory_' . $i, $ressourceCategory);
        }
        $manager->flush();
    }
}