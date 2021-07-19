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
        

        for($i = 0; $i < 3; $i++ ) {

            $hospital = new Hospital;
            $hospital->setName($faker->name());
            $hospital->setAddress($faker->streetAddress());
            $hospital->setCity($faker->city());
            $hospital->setZipcode($faker->postcode());
            $manager->persist($hospital);

            // for($j = 0; $j <= 10; $j++ ) {
            //     $user = $this->getReference('user_' . $j);
            //     if(($user->getRoles()[0] == 'ROLE_DOC') && (rand(0,10) < 4)){
            //         $hospital->addDoctor($user);
            //         $manager->persist($hospital);
            //     }
            // }

            if($i == 0){
                $user = $this->getReference('user_doc_1');
                $hospital->addDoctor($user);
                $user = $this->getReference('user_doc_2');
                $hospital->addDoctor($user);
                $manager->persist($hospital);

                for($j = 11; $j <= 15; $j++ ) {
                    $user = $this->getReference('user_'.$j);
                    $hospital->addDoctor($user);
                    $manager->persist($hospital);
                }
            }
            elseif($i == 1){
                $user = $this->getReference('user_doc_3');
                $hospital->addDoctor($user);
                $manager->persist($hospital);

                for($j = 16; $j <= 20; $j++ ) {
                    $user = $this->getReference('user_'.$j);
                    $hospital->addDoctor($user);
                    $manager->persist($hospital);
                }
            }
            else{
                $user = $this->getReference('user_doc_4');
                $hospital->addDoctor($user);
                $manager->persist($hospital);
                $user = $this->getReference('user_doc_5');
                $hospital->addDoctor($user);
                $manager->persist($hospital);

                for($j = 21; $j <= 25; $j++ ) {
                    $user = $this->getReference('user_'.$j);
                    $hospital->addDoctor($user);
                    $manager->persist($hospital);
                }
            }

            
        }
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