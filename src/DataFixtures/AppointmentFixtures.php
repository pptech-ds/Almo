<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Hospital;
use App\Entity\Appointment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AppointmentFixtures extends Fixture implements DependentFixtureInterface
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

        $dateStartFuture = DateTime::createFromFormat('Y-m-d H:i:s', '2021-12-10 09:00:00');
        $dateEndFuture = DateTime::createFromFormat('Y-m-d H:i:s', '2021-12-10 10:00:00');

        $dateStartPast = DateTime::createFromFormat('Y-m-d H:i:s', '2021-07-28 09:00:00');
        $dateEndPast = DateTime::createFromFormat('Y-m-d H:i:s', '2021-07-28 10:00:00');

        for($i = 0; $i<40; $i++ ) {
            $user = $this->getReference('user_pro_'.$i);

            for($j=0; $j<10; $j++){
                $disponility = new Appointment;
                $disponility->setContent($faker->realText($maxNbChars = 200, $indexSize = 2));
                $disponility->setStartTime($dateStartFuture);
                $disponility->setEndTime($dateEndFuture);
                $disponility->setCreatedBy($user);
                $disponility->setName('RV '.$user->getSpeciality());
                if($j%2==0){
                    $disponility->setIsVisio(1);
                } else {
                    $disponility->setIsVisio(0);
                }
                $manager->persist($disponility);
            }
        }

        for($i = 0; $i<10; $i++ ) {
            $user = $this->getReference('user_pro');

            
            $disponility = new Appointment;
            $disponility->setContent($faker->realText($maxNbChars = 200, $indexSize = 2));
            $disponility->setStartTime($dateStartFuture);
            $disponility->setEndTime($dateEndFuture);
            $disponility->setCreatedBy($user);
            $disponility->setName('RV '.$user->getSpeciality());
            if($i%2==0){
                $disponility->setIsVisio(1);
            } else {
                $disponility->setIsVisio(0);
            }
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