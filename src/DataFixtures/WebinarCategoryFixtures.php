<?php

namespace App\DataFixtures;

use App\Entity\WebinarCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WebinarCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $webinarCategories = ['Santé', 'Almo'];

        for($i=0; $i<count($webinarCategories); $i++) {
            $webinarCategory = new WebinarCategory;
            $webinarCategory->setName($webinarCategories[$i]);
            $manager->persist($webinarCategory);

            // Creating references to get them in ArtitFixtures
            $this->addReference('webinarCategory_' . $i, $webinarCategory);
        }
        $manager->flush();
    }
}