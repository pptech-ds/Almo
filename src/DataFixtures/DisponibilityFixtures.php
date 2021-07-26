<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Hospital;
use App\Entity\Disponibility;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DisponibilityFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        
        // print('Starting HospitalFixtures ');

        // unixTime($max = 'now')                // 58781813
        // dateTime($max = 'now', $timezone = null) // DateTime('2008-04-25 08:37:17', 'UTC')
        // dateTimeAD($max = 'now', $timezone = null) // DateTime('1800-04-29 20:38:49', 'Europe/Paris')
        // iso8601($max = 'now')                 // '1978-12-09T10:10:29+0000'
        // date($format = 'Y-m-d', $max = 'now') // '1979-06-09'
        // time($format = 'H:i:s', $max = 'now') // '20:49:42'
        // dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null) // DateTime('2003-03-15 02:00:49', 'Africa/Lagos')
        // dateTimeInInterval($startDate = '-30 years', $interval = '+ 5 days', $timezone = null) // DateTime('2003-03-15 02:00:49', 'Antartica/Vostok')
        // dateTimeThisCentury($max = 'now', $timezone = null)     // DateTime('1915-05-30 19:28:21', 'UTC')
        // dateTimeThisDecade($max = 'now', $timezone = null)      // DateTime('2007-05-29 22:30:48', 'Europe/Paris')
        // dateTimeThisYear($max = 'now', $timezone = null)        // DateTime('2011-02-27 20:52:14', 'Africa/Lagos')
        // dateTimeThisMonth($max = 'now', $timezone = null)       // DateTime('2011-10-23 13:46:23', 'Antarctica/Vostok')

        $date1 = DateTime::createFromFormat('Y-m-d H:i:s', '2021-07-28 09:00:00');
        $date2 = DateTime::createFromFormat('Y-m-d H:i:s', '2021-07-28 10:00:00');

        for($i = 0; $i < 50; $i++ ) {

            $disponility = new Disponibility;
            $user = $this->getReference('user_pro_' . $faker->numberBetween(11, 30));
            $disponility->setName($faker->name());
            $disponility->setContent($faker->realText($maxNbChars = 200, $indexSize = 2));
            $disponility->setStartTime($date1);
            $disponility->setEndTime($date2);
            $disponility->setCreatedBy($user);
            $disponility->setIsVisio($faker->numberBetween(0,1));
            $manager->persist($disponility);
        }

        // print('End HospitalFixtures ');

        $manager->flush();
    }

    public function getDependencies()
    {
        // dependencies
        return [
            SpecialityFixtures::class,
            UserFixtures::class,
        ];
    }
}