<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\Ressource;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\RessourceCategoryFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RessourceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // print('Starting RessourceFixtures ');

        for($i = 0; $i <= 200; $i++ ) {
            // getting references created in RessourceCategoryFixtures
            $ressourceCategory = $this->getReference('ressourceCategory_' . $faker->numberBetween(0, 1));
            $user = $this->getReference('user_pro_' . $faker->numberBetween(1, 10));
            $ressource = new Ressource;
            $ressource->setRessourceCategory($ressourceCategory);
            $ressource->setTitle($faker->catchPhrase());
            $ressource->setContent($faker->realText($maxNbChars = 200, $indexSize = 2));
            // $ressource->setImage($faker->imageUrl($width = 640, $height = 480));
            $ressource->setImage('img/demo'.rand(1,18).'.jpg');
            $ressource->setActive($faker->numberBetween(0,1));
            $ressource->setUser($user);
            $manager->persist($ressource);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // dependencies
        return [
            SpecialityFixtures::class,
            RessourceCategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}