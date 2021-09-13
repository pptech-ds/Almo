<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Webinar;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\WebinarCategoryFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class WebinarFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // print('Starting WebinarFixtures ');

        $dateFuture = DateTime::createFromFormat('Y-m-d H:i:s', '2021-12-28 09:00:00');
        $datePast = DateTime::createFromFormat('Y-m-d H:i:s', '2021-07-28 09:00:00');

        for($i = 0; $i < 3; $i++ ) {
            // getting references created in WebinarCategoryFixtures
            $webinarCategory = $this->getReference('webinarCategory');
            $user = $this->getReference('user_pro');
            $webinar = new Webinar;
            $webinar->setWebinarCategory($webinarCategory);
            $webinar->setTitle('Webinar "'.$faker->catchPhrase().'"');
            $webinar->setContent($faker->realText($maxNbChars = 500, $indexSize = 2));
            if($i%2 == 0){
                $webinar->setStartTime($dateFuture);
            }
            else {
                $webinar->setStartTime($datePast);
            }
            $webinar->setImage('img/demo'.rand(1,18).'.jpg');
            $webinar->setActive(true);
            $webinar->setUser($user);
            $webinar->setHost($user);
            $webinar->setVisioLink('https://meet.google.com/yze-zkvf-mjy');
            $manager->persist($webinar);
        }


        $manager->flush();
    }

    public function getDependencies()
    {
        // dependencies
        return [
            SpecialityFixtures::class,
            WebinarCategoryFixtures::class,
            UserFixtures::class,         
        ];
    }
}