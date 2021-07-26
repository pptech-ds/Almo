<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Hospital;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class HospitalFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        
        // print('Starting HospitalFixtures ');

        for($i = 0; $i < 4; $i++ ) {

            $hospital = new Hospital;
            $hospital->setName($faker->name());
            $hospital->setAddress($faker->streetAddress());
            $hospital->setCity($faker->city());
            $hospital->setZipcode($faker->postcode());
            $manager->persist($hospital);
            $this->addReference('hospital_'.($i), $hospital);


            // if($i == 0){
            //     $user = $this->getReference('user_doc_1');
            //     $hospital->addUser($user);
            //     $user = $this->getReference('user_doc_4');
            //     $hospital->addUser($user);
            //     $manager->persist($hospital);

            //     for($j = 31; $j <= 35; $j++ ) {
            //         $user = $this->getReference('user_'.$j);
            //         $hospital->addUser($user);
            //         $manager->persist($hospital);
            //     }
            // }
            // elseif($i == 1){
            //     $user = $this->getReference('user_doc_5');
            //     $hospital->addUser($user);
            //     $manager->persist($hospital);

            //     for($j = 36; $j <= 40; $j++ ) {
            //         $user = $this->getReference('user_'.$j);
            //         $hospital->addUser($user);
            //         $manager->persist($hospital);
            //     }
            // }
            // else{
            //     $user = $this->getReference('user_doc_6');
            //     $hospital->addUser($user);
            //     $manager->persist($hospital);
            //     $user = $this->getReference('user_doc_7');
            //     $hospital->addUser($user);
            //     $manager->persist($hospital);

            //     for($j = 41; $j <= 45; $j++ ) {
            //         $user = $this->getReference('user_'.$j);
            //         $hospital->addUser($user);
            //         $manager->persist($hospital);
            //     }
            // }

            
        }

        // print('End HospitalFixtures ');

        $manager->flush();
    }

    // public function getDependencies()
    // {
    //     // dependencies
    //     return [
    //         SpecialityFixtures::class,
    //         UserFixtures::class,
    //     ];
    // }
}