<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\City\CityEntity;
use App\Session\SessionEntity;
use App\Session\StageEntity;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SessionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('uk_UA');

        $cities = $manager->getRepository(CityEntity::class)->findAll();
        foreach ($cities as $city){
            $cnt = random_int(2, 4);
            for($i = 0; $i <= $cnt; $i++){
                $end    = null;
                $start  = (new DateTime())->modify('-'. random_int(0, 5) .' weeks')->modify('-' . $i . ' years');
                $stages = ['submission', 'review', 'voting', 'decision', 'implementation'];

                $session = new SessionEntity(
                    name: "Бюджет міста " . date('Y', $start->getTimestamp()),
                    city: $city,
                    startDate: $start,
                    endDate: $start
                );

                $manager->persist($session);

                foreach ($stages as $key => $stageName){
                    $stage = new StageEntity(
                        $stageName,
                        $session,
                        true,
                        $end
                            ? (clone $end)->modify('+1 day')->setTime(0,0)
                            : (clone $start)->modify("+$key week")->setTime(0,0),
                        $end = (clone $start)->modify("+$key week")->modify('+2 week')->setTime(23,59),
                    );

                    $manager->persist($stage);
                }

                $session->setEndDate($end);

                $manager->persist($session);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CityFixtures::class,
        ];
    }
}
