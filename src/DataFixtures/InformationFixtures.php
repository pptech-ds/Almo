<?php

namespace App\DataFixtures;


use App\Entity\Information;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class InformationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $user = $this->getReference('user_admin');

        $almo_intro_1 = new Information;
        $almo_intro_1->setCreatedBy($user);
        $almo_intro_1->setTitle('almo_intro_1');
        $almo_intro_1->setContent('Almo est une platforme en ligne dédiée à la santé mentale des personnes atteintes de cancer.');
        $almo_intro_1->setIsActive(true);
        $manager->persist($almo_intro_1);

        $almo_intro_2 = new Information;
        $almo_intro_2->setCreatedBy($user);
        $almo_intro_2->setTitle('almo_intro_2');
        $almo_intro_2->setContent('Grâce à Almo, où que vous soyez en France, vous pouvez accéder 24h/24 et 7j/7 à des réponses concrètes, adaptées et personnalisées. Avec nos coaches et praticiens, profitez des bienfaits de l’hypnose, de l’EMDR, de la méditation de pleine conscience, du yoga thérapeutique, de la sexologie… ou de simples consultations de psychologie pour vous ou vos proches.');
        $almo_intro_2->setIsActive(true);
        $manager->persist($almo_intro_2);

        $almo_experiment = new Information;
        $almo_experiment->setCreatedBy($user);
        $almo_experiment->setTitle('almo_experiment');
        $almo_experiment->setContent('A changer une fois deployer (Pras)');
        $almo_experiment->setIsActive(true);
        $manager->persist($almo_experiment);

        
        $manager->flush();
    }

    public function getDependencies()
    {
        // dependencies
        return [
            UserFixtures::class,
        ];
    }
}