<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Webinar;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\WebinarCategoryFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class WebinarFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        // print('Starting WebinarFixtures ');

        for($i = 1; $i <= 200; $i++ ) {
            // getting references created in WebinarCategoryFixtures
            $webinarCategory = $this->getReference('webinarCategory_' . $faker->numberBetween(0, 1));
            $user = $this->getReference('user_pro_' . $faker->numberBetween(9, 10));
            $webinar = new Webinar;
            $webinar->setWebinarCategory($webinarCategory);
            $webinar->setTitle($faker->catchPhrase());
            $webinar->setContent($faker->realText($maxNbChars = 200, $indexSize = 2));
            $webinar->setImage($faker->imageUrl($width = 640, $height = 480));
            $webinar->setActive($faker->numberBetween(0,1));
            $webinar->setUser($user);
            $manager->persist($webinar);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // dependencies
        return [
            WebinarCategoryFixtures::class,
            UserFixtures::class            
        ];
    }
}