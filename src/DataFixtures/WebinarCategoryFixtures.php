<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\WebinarCategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class WebinarCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $webinarCategories = ['Santé', 'Almo'];

        $faker = Factory::create('fr_FR'); 

        for($i=0; $i<count($webinarCategories); $i++) {
            $webinarCategory = new WebinarCategory;
            $webinarCategory->setName($webinarCategories[$i]);
            $webinarCategory->setDescription($faker->realText($maxNbChars = 200, $indexSize = 2));
            $webinarCategory->setImage($faker->imageUrl($width = 640, $height = 480));
            $manager->persist($webinarCategory);

            // Creating references to get them in ArtitFixtures
            $this->addReference('webinarCategory_' . $i, $webinarCategory);
        }
        $manager->flush();
    }
}