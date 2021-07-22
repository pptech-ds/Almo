<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Hospital;
use App\Entity\Ressource;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\RessourceCategoryFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class HospitalFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        
        // print('Starting HospitalFixtures ');

        for($i = 0; $i < 4; $i++ ) {

            $hospital = new Hospital;
            $hospital->setName($faker->name());
            $hospital->setAddress($faker->streetAddress());
            $hospital->setCity($faker->city());
            $hospital->setZipcode($faker->postcode());
            $manager->persist($hospital);


            if($i == 0){
                $user = $this->getReference('user_doc_1');
                $hospital->addUser($user);
                $user = $this->getReference('user_doc_2');
                $hospital->addUser($user);
                $manager->persist($hospital);

                for($j = 11; $j <= 15; $j++ ) {
                    $user = $this->getReference('user_'.$j);
                    $hospital->addUser($user);
                    $manager->persist($hospital);
                }
            }
            elseif($i == 1){
                $user = $this->getReference('user_doc_3');
                $hospital->addUser($user);
                $manager->persist($hospital);

                for($j = 16; $j <= 20; $j++ ) {
                    $user = $this->getReference('user_'.$j);
                    $hospital->addUser($user);
                    $manager->persist($hospital);
                }
            }
            else{
                $user = $this->getReference('user_doc_4');
                $hospital->addUser($user);
                $manager->persist($hospital);
                $user = $this->getReference('user_doc_5');
                $hospital->addUser($user);
                $manager->persist($hospital);

                for($j = 21; $j <= 25; $j++ ) {
                    $user = $this->getReference('user_'.$j);
                    $hospital->addUser($user);
                    $manager->persist($hospital);
                }
            }

            
        }

        // print('End HospitalFixtures ');

        $manager->flush();
    }

    public function getDependencies()
    {
        // dependencies
        return [
            UserFixtures::class            
        ];
    }
}