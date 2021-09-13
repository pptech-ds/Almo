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


        for($i = 0; $i <= 3; $i++){
            
            
            // le premier de la liste est admin
            if($i === 0){
                $user = new User;
                $user->setRoles(['ROLE_ADMIN']);
                $user->setEmail('admin@almo.com');
                $user->setIsVerified(1);
                $user->setCivility('Mr');
                $user->setPassword($this->encoder->hashPassword($user, '123456'));
                $user->setFirstname('Admin');
                $user->setlastname('Almo');
                $user->setAddress($faker->streetAddress());
                $user->setCity($faker->city());
                $user->setZipcode($faker->postcode());
                $user->setPhone($faker->phoneNumber());
                $user->setDetails($faker->realText($maxNbChars = 2000, $indexSize = 2));
                $user->setVisioLink('https://meet.google.com/yze-zkvf-mjy');
                $manager->persist($user);
                $this->addReference('user_admin', $user);
            } elseif($i === 1){
                $user = new User;
                $user->setRoles(['ROLE_DOC']);
                $user->setEmail('user_doc@almo.com');
                $user->setIsVerified(1);
                $user->setCivility('Dr');
                $user->setPassword($this->encoder->hashPassword($user, '123456'));
                $user->setFirstname('User');
                $user->setlastname('Doc');
                $user->setAddress($faker->streetAddress());
                $user->setCity($faker->city());
                $user->setZipcode($faker->postcode());
                $user->setPhone($faker->phoneNumber());
                $user->setDetails($faker->realText($maxNbChars = 2000, $indexSize = 2));
                $user->setHospital($this->getReference('hospital'));
                $user->setVisioLink('https://meet.google.com/yze-zkvf-mjy');
                $manager->persist($user);
                $this->addReference('user_doc', $user);
            } elseif($i === 2){
                $user = new User;
                $user->setRoles(['ROLE_PRO']);
                $user->setEmail('user_pro@almo.com');
                $user->setIsVerified(1);
                $user->setCivility('Mr');
                $user->setSpeciality($this->getReference('speciality'));
                $user->setPassword($this->encoder->hashPassword($user, '123456'));
                $user->setFirstname('User');
                $user->setlastname('Pro');
                $user->setAddress($faker->streetAddress());
                $user->setCity($faker->city());
                $user->setZipcode($faker->postcode());
                $user->setPhone($faker->phoneNumber());
                $user->setDetails($faker->realText($maxNbChars = 2000, $indexSize = 2));
                $user->setVisioLink('https://meet.google.com/yze-zkvf-mjy');
                $manager->persist($user);
                $this->addReference('user_pro', $user);
            } elseif($i === 3){
                $user = new User;
                $user->setRoles(['ROLE_PATIENT']);
                $user->setEmail('user_patient@almo.com');
                $user->setIsVerified(1);
                $user->setCivility('Mr');
                $user->setPassword($this->encoder->hashPassword($user, '123456'));
                $user->setFirstname('User');
                $user->setlastname('Patient');
                $user->setAddress($faker->streetAddress());
                $user->setCity($faker->city());
                $user->setZipcode($faker->postcode());
                $user->setPhone($faker->phoneNumber());
                $user->setDetails($faker->realText($maxNbChars = 2000, $indexSize = 2));
                $user->setHospital($this->getReference('hospital'));
                $user->setDoctor($this->getReference('user_doc'));
                $manager->persist($user);
                $this->addReference('user_patient', $user);
            } 
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