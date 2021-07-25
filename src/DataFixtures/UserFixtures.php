<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR'); 


        for($i = 0; $i <= 30; $i++){
            $user = new User;
            
            // le premier de la liste est admin
            if($i === 0){
                $user->setRoles(['ROLE_ADMIN']);
                $user->setEmail('admin@almo.com');
                $user->setIsVerified(1);
                $user->setCivility('Mr');
                $this->addReference('user_admin_' . $i, $user);
            } elseif($i === 1){
                $user->setRoles(['ROLE_DOC']);
                $user->setEmail('user_doc@almo.com');
                $user->setIsVerified(1);
                $user->setCivility('Dr');
                $this->addReference('user_doc_' . $i, $user);
            } elseif($i === 2){
                $user->setRoles(['ROLE_PRO']);
                $user->setEmail('user_pro@almo.com');
                $user->setIsVerified(1);
                $user->setCivility('Mr');
                $this->addReference('user_pro_' . $i, $user);
            } elseif($i === 3){
                $user->setRoles(['ROLE_PATIENT']);
                $user->setEmail('user_patient@almo.com');
                $user->setIsVerified(1);
                $user->setCivility('Mr');
                $this->addReference('user_patient_' . $i, $user);
            } elseif (($i > 3) && ($i <= 10)) {
                $user->setRoles(['ROLE_DOC']);
                $user->setEmail($faker->email);
                $user->setIsVerified($faker->numberBetween(0,1));
                $user->setCivility('Dr');
                $user->setSpeciality($this->getReference('speciality_' . $faker->numberBetween(0, 4)));
                $this->addReference('user_doc_' . $i, $user);
            } else {
                $user->setRoles(['ROLE_PRO']);
                $user->setEmail($faker->email);
                $user->setIsVerified($faker->numberBetween(0,1));
                $user->setCivility('Mme');
                $user->setSpeciality($this->getReference('speciality_' . $faker->numberBetween(5, 9)));
                $this->addReference('user_pro_' . $i, $user);
            } 

            $user->setPassword($this->encoder->hashPassword($user, '123456'));
            $user->setFirstname($faker->firstName());
            $user->setlastname($faker->lastName());
            $user->setAddress($faker->streetAddress());
            $user->setCity($faker->city());
            $user->setZipcode($faker->postcode());
            $user->setPhone($faker->phoneNumber());
            $user->setDetails($faker->realText($maxNbChars = 500, $indexSize = 2));
            $manager->persist($user);
   
        }

        // print_r($this->getReference('user_doc_2'));

        for($i = 31; $i <= 36; $i++){
            $user = new User;
            $user->setRoles(['ROLE_PATIENT']);
            $user->setEmail($faker->email);
            $user->setIsVerified($faker->numberBetween(0,1));
            $user->setCivility('M');
            $user->setPassword($this->encoder->hashPassword($user, '123456'));
            $user->setFirstname($faker->firstName());
            $user->setlastname($faker->lastName());
            $user->setAddress($faker->streetAddress());
            $user->setCity($faker->city());
            $user->setZipcode($faker->postcode());
            $user->setPhone($faker->phoneNumber());
            $user->setDoctor($this->getReference('user_doc_1'));
            $user->setDetails($faker->realText($maxNbChars = 500, $indexSize = 2));
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
   
        }


        for($i = 37; $i <= 40; $i++){
            $user = new User;
            $user->setRoles(['ROLE_PATIENT']);
            $user->setEmail($faker->email);
            $user->setIsVerified($faker->numberBetween(0,1));
            $user->setCivility('Mme');
            $user->setPassword($this->encoder->hashPassword($user, '123456'));
            $user->setFirstname($faker->firstName());
            $user->setlastname($faker->lastName());
            $user->setAddress($faker->streetAddress());
            $user->setCity($faker->city());
            $user->setZipcode($faker->postcode());
            $user->setPhone($faker->phoneNumber());
            $user->setDoctor($this->getReference('user_doc_4'));
            $user->setDetails($faker->realText($maxNbChars = 500, $indexSize = 2));
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
   
        }


        for($i = 41; $i <= 45; $i++){
            $user = new User;
            $user->setRoles(['ROLE_PATIENT']);
            $user->setEmail($faker->email);
            $user->setIsVerified($faker->numberBetween(0,1));
            $user->setCivility('M');
            $user->setPassword($this->encoder->hashPassword($user, '123456'));
            $user->setFirstname($faker->firstName());
            $user->setlastname($faker->lastName());
            $user->setAddress($faker->streetAddress());
            $user->setCity($faker->city());
            $user->setZipcode($faker->postcode());
            $user->setPhone($faker->phoneNumber());
            $user->setDoctor($this->getReference('user_doc_5'));
            $user->setDetails($faker->realText($maxNbChars = 500, $indexSize = 2));
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
   
        }

        // print('End UserFixtures ');

        $manager->flush();      
        
        // print('End UserFixtures after flush ');
    }



    public function getDependencies()
    {
        // dependencies
        return [
            SpecialityFixtures::class
        ];
    }
}