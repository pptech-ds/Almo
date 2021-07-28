<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\DataFixtures\HospitalFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture implements DependentFixtureInterface
{
    
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR'); 


        for($i = 0; $i <= 11; $i++){
            
            
            // le premier de la liste est admin
            if($i === 0){
                $user = new User;
                $user->setRoles(['ROLE_ADMIN']);
                $user->setEmail('admin@almo.com');
                $user->setIsVerified(1);
                $user->setCivility('Mr');
                $user->setPassword($this->encoder->hashPassword($user, '123456'));
                $user->setFirstname($faker->firstName());
                $user->setlastname($faker->lastName());
                $user->setAddress($faker->streetAddress());
                $user->setCity($faker->city());
                $user->setZipcode($faker->postcode());
                $user->setPhone($faker->phoneNumber());
                $user->setDetails($faker->realText($maxNbChars = 500, $indexSize = 2));
                $manager->persist($user);
                $this->addReference('user_admin', $user);
            } elseif($i === 1){
                $user = new User;
                $user->setRoles(['ROLE_DOC']);
                $user->setEmail('user_doc@almo.com');
                $user->setIsVerified(1);
                $user->setCivility('Dr');
                $user->setPassword($this->encoder->hashPassword($user, '123456'));
                $user->setFirstname($faker->firstName());
                $user->setlastname($faker->lastName());
                $user->setAddress($faker->streetAddress());
                $user->setCity($faker->city());
                $user->setZipcode($faker->postcode());
                $user->setPhone($faker->phoneNumber());
                $user->setDetails($faker->realText($maxNbChars = 500, $indexSize = 2));
                $user->setHospital($this->getReference('hospital_0'));
                $manager->persist($user);
                $this->addReference('user_doc', $user);
            } elseif($i === 2){
                $user = new User;
                $user->setRoles(['ROLE_PRO']);
                $user->setEmail('user_pro@almo.com');
                $user->setIsVerified(1);
                $user->setCivility('Mr');
                $user->setSpeciality($this->getReference('speciality_0'));
                $user->setPassword($this->encoder->hashPassword($user, '123456'));
                $user->setFirstname($faker->firstName());
                $user->setlastname($faker->lastName());
                $user->setAddress($faker->streetAddress());
                $user->setCity($faker->city());
                $user->setZipcode($faker->postcode());
                $user->setPhone($faker->phoneNumber());
                $user->setDetails($faker->realText($maxNbChars = 500, $indexSize = 2));
                $manager->persist($user);
                $this->addReference('user_pro', $user);
            } elseif($i === 3){
                $user = new User;
                $user->setRoles(['ROLE_PATIENT']);
                $user->setEmail('user_patient@almo.com');
                $user->setIsVerified(1);
                $user->setCivility('Mr');
                $user->setPassword($this->encoder->hashPassword($user, '123456'));
                $user->setFirstname($faker->firstName());
                $user->setlastname($faker->lastName());
                $user->setAddress($faker->streetAddress());
                $user->setCity($faker->city());
                $user->setZipcode($faker->postcode());
                $user->setPhone($faker->phoneNumber());
                $user->setDetails($faker->realText($maxNbChars = 500, $indexSize = 2));
                $user->setHospital($this->getReference('hospital_1'));
                $manager->persist($user);
                $this->addReference('user_patient', $user);
            } elseif (($i > 3) && ($i <= 10)) {
                $user = new User;
                $user->setRoles(['ROLE_DOC']);
                $user->setEmail($faker->email);
                $user->setIsVerified($faker->numberBetween(0,1));
                $user->setCivility('Dr');
                $user->setSpeciality($this->getReference('speciality_' . $faker->numberBetween(0, 4)));
                $user->setPassword($this->encoder->hashPassword($user, '123456'));
                $user->setFirstname($faker->firstName());
                $user->setlastname($faker->lastName());
                $user->setAddress($faker->streetAddress());
                $user->setCity($faker->city());
                $user->setZipcode($faker->postcode());
                $user->setPhone($faker->phoneNumber());
                $user->setDetails($faker->realText($maxNbChars = 500, $indexSize = 2));
                if($i%3 == 0){
                    $user->setHospital($this->getReference('hospital_0'));
                } elseif($i%3 == 1) {
                    $user->setHospital($this->getReference('hospital_1'));
                } elseif($i%3 == 2) {
                    $user->setHospital($this->getReference('hospital_2'));
                } else{
                    $user->setHospital($this->getReference('hospital_3'));
                }
                $manager->persist($user);
                $this->addReference('user_doc_' . $i, $user);
            } else {
                // print('test in 11');
                $count = 0;
                for($j=0; $j<10; $j++){
                    // print('test in '.$count);
                    for($k=0; $k<4; $k++){
                        $user = new User;
                        $user->setRoles(['ROLE_PRO']);
                        $user->setEmail($faker->email);
                        if($k%2==0){
                            $user->setIsVerified(1);
                            $user->setCivility('Mme');
                        }
                        else {
                            $user->setIsVerified(0);
                            $user->setCivility('Mr');
                        }
                        $user->setSpeciality($this->getReference('speciality_' . $j));
                        $user->setPassword($this->encoder->hashPassword($user, '123456'));
                        $user->setFirstname($faker->firstName());
                        $user->setlastname($faker->lastName());
                        $user->setAddress($faker->streetAddress());
                        $user->setCity($faker->city());
                        $user->setZipcode($faker->postcode());
                        $user->setPhone($faker->phoneNumber());
                        $user->setDetails($faker->realText($maxNbChars = 500, $indexSize = 2));
                        $manager->persist($user);
                        // print('user_pro_'.($count));
                        $this->addReference('user_pro_'.($count), $user);
                        $count++;
                    }
                }
            } 
        }

        for($i = 0; $i <= 29; $i++){
            $user = new User;
            $user->setRoles(['ROLE_PATIENT']);
            $user->setEmail($faker->email);
            $user->setIsVerified($faker->numberBetween(0,1));
            if($i%2 == 0){
                $user->setCivility('Mr');
            } else {
                $user->setCivility('Mme');
            }
            $user->setPassword($this->encoder->hashPassword($user, '123456'));
            $user->setFirstname($faker->firstName());
            $user->setlastname($faker->lastName());
            $user->setAddress($faker->streetAddress());
            $user->setCity($faker->city());
            $user->setZipcode($faker->postcode());
            $user->setPhone($faker->phoneNumber());
            if($i%6 == 0){
                $user->setDoctor($this->getReference('user_doc_4'));
            } elseif($i%6 == 1) {
                $user->setDoctor($this->getReference('user_doc_5'));
            } elseif($i%6 == 2) {
                $user->setDoctor($this->getReference('user_doc_6'));
            } elseif($i%6 == 3) {
                $user->setDoctor($this->getReference('user_doc_7'));
            } elseif($i%6 == 4) {
                $user->setDoctor($this->getReference('user_doc_8'));
            } elseif($i%6 == 5) {
                $user->setDoctor($this->getReference('user_doc_9'));
            }
            $user->setDetails($faker->realText($maxNbChars = 500, $indexSize = 2));
            if($i%3 == 0){
                $user->setHospital($this->getReference('hospital_0'));
            } elseif($i%3 == 1) {
                $user->setHospital($this->getReference('hospital_1'));
            } elseif($i%3 == 2) {
                $user->setHospital($this->getReference('hospital_2'));
            } else{
                $user->setHospital($this->getReference('hospital_3'));
            }
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }
        $manager->flush();         
    }



    public function getDependencies()
    {
        // dependencies
        return [
            HospitalFixtures::class,
            SpecialityFixtures::class,
        ];
    }
}