<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\RessourceCategoryFixtures;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR'); //population de jeu de fausses données en français


        for($i = 0; $i <= 10; $i++){
            $user = new User;
            
            

            // le premier de la liste est admin
            if($i === 0){
                $user->setRoles(['ROLE_ADMIN']);
                $user->setEmail('admin@almo.com');
                $user->setIsVerified(1);
                $user->setCivility('Mr');
                $this->addReference('user_admin_' . $i, $user);
            } elseif (($i > 0) && ($i <= 5)) {
                $user->setRoles(['ROLE_DOC']);
                $user->setEmail($faker->email);
                $user->setIsVerified($faker->numberBetween(0,1));
                $user->setCivility('Dr');
                $this->addReference('user_doc_' . $i, $user);
            } else {
                $user->setRoles(['ROLE_PRO']);
                $user->setEmail($faker->email);
                $user->setIsVerified($faker->numberBetween(0,1));
                $user->setCivility('Mme');
                $this->addReference('user_pro_' . $i, $user);
            } 

            $user->setPassword($this->encoder->encodePassword($user, '123456'));
            $user->setFirstname($faker->firstName());
            $user->setlastname($faker->lastName());
            $user->setAddress($faker->streetAddress());
            $user->setCity($faker->city());
            $user->setZipcode($faker->postcode());
            $user->setPhone($faker->phoneNumber());
            $manager->persist($user);
   
        }

        // print_r($this->getReference('user_doc_2'));

        for($i = 11; $i <= 16; $i++){
            $user = new User;
            $user->setRoles(['ROLE_PATIENT']);
            $user->setEmail($faker->email);
            $user->setIsVerified($faker->numberBetween(0,1));
            $user->setCivility('M');
            $user->setPassword($this->encoder->encodePassword($user, '123456'));
            $user->setFirstname($faker->firstName());
            $user->setlastname($faker->lastName());
            $user->setAddress($faker->streetAddress());
            $user->setCity($faker->city());
            $user->setZipcode($faker->postcode());
            $user->setPhone($faker->phoneNumber());
            $user->setDoctor($this->getReference('user_doc_2'));
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
   
        }


        for($i = 17; $i <= 20; $i++){
            $user = new User;
            $user->setRoles(['ROLE_PATIENT']);
            $user->setEmail($faker->email);
            $user->setIsVerified($faker->numberBetween(0,1));
            $user->setCivility('Mme');
            $user->setPassword($this->encoder->encodePassword($user, '123456'));
            $user->setFirstname($faker->firstName());
            $user->setlastname($faker->lastName());
            $user->setAddress($faker->streetAddress());
            $user->setCity($faker->city());
            $user->setZipcode($faker->postcode());
            $user->setPhone($faker->phoneNumber());
            $user->setDoctor($this->getReference('user_doc_1'));
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
   
        }


        for($i = 21; $i <= 25; $i++){
            $user = new User;
            $user->setRoles(['ROLE_PATIENT']);
            $user->setEmail($faker->email);
            $user->setIsVerified($faker->numberBetween(0,1));
            $user->setCivility('M');
            $user->setPassword($this->encoder->encodePassword($user, '123456'));
            $user->setFirstname($faker->firstName());
            $user->setlastname($faker->lastName());
            $user->setAddress($faker->streetAddress());
            $user->setCity($faker->city());
            $user->setZipcode($faker->postcode());
            $user->setPhone($faker->phoneNumber());
            $user->setDoctor($this->getReference('user_doc_3'));
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
   
        }

        // print('End UserFixtures ');

        $manager->flush();      
        
        // print('End UserFixtures after flush ');
    }
}