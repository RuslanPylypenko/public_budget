<?php

namespace App\DataFixtures;

use App\City\CityEntity;
use App\Project\Address\AddressEntity;
use App\Project\ProjectEntity;
use App\Project\Uploader\File;
use App\Project\Uploader\FileUploader;
use App\Session\SessionEntity;
use App\User\UserEntity;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly FileUploader $fileUploader,
        private readonly Client $client,
    ) {
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('uk_UA');
        $faker->addProvider(new PicsumPhotosProvider($faker));

        $users = $manager->getRepository(UserEntity::class)
            ->findAll();

        /** @var CityEntity $city */
        $sessions = $manager->getRepository(SessionEntity::class)->findAll();


        foreach ($sessions as $session){
            for ($i = 1; $i <= 20; $i++) {

                $project = new ProjectEntity(
                    number: $i,
                    status: $faker->randomElement([
                        ProjectEntity::STATUS_IMPLEMENTED,
                        ProjectEntity::STATUS_IMPOSSIBLE,
                        ProjectEntity::STATUS_REJECTED,
                        ProjectEntity::STATUS_TAKE_PART,
                        ProjectEntity::STATUS_REJECTED_FULLY,
                    ]),
                    budget: $faker->randomFloat(nbMaxDecimals: 2, min: 10000, max: 1000000),
                    name: $faker->sentence(5),
                    short: $faker->sentences(5, true),
                    description: $faker->sentences(20, true),
                    author: $faker->randomElement($users),
                    session: $session,
                );

                $city = $session->getCity();

                if ($faker->boolean()) {
                    $address = new AddressEntity(
                        $project,
                        null,
                        null,
                        null,
                        $city->getName(),
                        null,
                        "вул. " . $faker->streetName(),
                        $faker->buildingNumber(),
                        $faker->boolean() ? "кв. " . $faker->numberBetween(1, 120) : null,
                        null,
                        $faker->latitude($city->getLat() - 0.02, $city->getLat() + 0.02),
                        $faker->longitude($city->getLon() - 0.02, $city->getLon() + 0.02),
                    );

                    $manager->persist($address);
                }

                $manager->persist($project);
                $manager->flush();


                $response = $this->client->get($faker->imageUrl(500,500));
                $temporaryFilePath = tempnam(sys_get_temp_dir(), 'image_');
                file_put_contents($temporaryFilePath, $response->getBody());

                $uploadedFile = new UploadedFile($temporaryFilePath, 'image.png');
                $image = $this->fileUploader->uploadProjectImage($uploadedFile, $project);

                $project->addImage($image->getFileName());

                $manager->persist($project);
                $manager->flush();
            }
        }



        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SessionFixtures::class,
            UserFixtures::class,
        ];
    }
}