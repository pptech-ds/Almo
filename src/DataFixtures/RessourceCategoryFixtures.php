<?php

namespace App\DataFixtures;

use App\Entity\RessourceCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RessourceCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $RessourceCategories = ['Article', 'Document'];

        for($i=0; $i<count($RessourceCategories); $i++) {
            $ressourceCategory = new RessourceCategory;
            $ressourceCategory->setName($RessourceCategories[$i]);
            $manager->persist($ressourceCategory);

            // Creating references to get them in ArtitFixtures
            $this->addReference('ressourceCategory_' . $i, $ressourceCategory);
        }
        $manager->flush();
    }
}