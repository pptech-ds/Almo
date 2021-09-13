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
        // $ressourceCategories = ['Article'];

        $faker = Factory::create('fr_FR'); 

        $ressourceCategory = new RessourceCategory;
        $ressourceCategory->setName('Article');
        $ressourceCategory->setDescription($faker->realText($maxNbChars = 200, $indexSize = 2));
        // $ressourceCategory->setImage($faker->imageUrl($width = 640, $height = 480));
        $ressourceCategory->setImage('img/demo'.rand(1,18).'.jpg');
        $manager->persist($ressourceCategory);

        // Creating references to get them in ArtitFixtures
        $this->addReference('ressourceCategory', $ressourceCategory);
        $manager->flush();
    }
}