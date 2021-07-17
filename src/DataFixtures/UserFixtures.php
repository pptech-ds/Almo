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

        for($nbUsers = 0; $nbUsers <= 25; $nbUsers++){
            $user = new User;
            
            // le premier de la liste est admin
            if($nbUsers === 0){
                $user->setRoles(['ROLE_ADMIN']);
                $user->setEmail('admin@almo.com');
                $user->setIsVerified(1);
                $this->addReference('user_admin_' . $nbUsers, $user);
            } elseif (($nbUsers > 0) && ($nbUsers <= 5)) {
                $user->setRoles(['ROLE_DOC']);
                $user->setEmail($faker->email);
                $user->setIsVerified($faker->numberBetween(0,1));
                $this->addReference('user_doc_' . $nbUsers, $user);
            } elseif (($nbUsers > 5) && ($nbUsers <= 10)) {
                $user->setRoles(['ROLE_PRO']);
                $user->setEmail($faker->email);
                $user->setIsVerified($faker->numberBetween(0,1));
                $this->addReference('user_pro_' . $nbUsers, $user);
            } else {
                $user->setRoles(['ROLE_USER']);
                $user->setEmail($faker->email);
                $user->setIsVerified($faker->numberBetween(0,1));
                $this->addReference('user_' . $nbUsers, $user);
            }
            // tous les users ont le même mot de passe
            $user->setPassword($this->encoder->encodePassword($user, '123456'));
            $user->setFirstname($faker->firstName());
            $user->setlastname($faker->lastName());
            $user->setAddress($faker->streetAddress());
            $user->setCity($faker->city());
            $user->setZipcode($faker->postcode());
            $user->setPhone($faker->phoneNumber());
            // if($nbUsers % 2 == 0){
            //     $user->setHospital(['Hopital de Paris']);
            // } else {
            //     $user->setHospital(['Hopital de Marseille']);
            // }
            // $user->setDoctor([$faker->name()]);
            $manager->persist($user);

            // Creating references to get them in ArtitFixtures
            
        }
        $manager->flush();        
    }
}