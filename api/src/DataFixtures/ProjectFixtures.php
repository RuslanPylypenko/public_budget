<?php

namespace App\DataFixtures;

use App\City\CityEntity;
use App\Project\Address\AddressEntity;
use App\Project\Category;
use App\Project\ProjectEntity;
use App\Project\ProjectStatus;
use App\Project\Uploader\FileUploader;
use App\Session\SessionEntity;
use App\User\UserEntity;
use Bluemmb\Faker\PicsumPhotosProvider;
use DavidBadura\FakerMarkdownGenerator\FakerProvider as FakerProvider;
use DavidBadura\MarkdownBuilder\MarkdownBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Kernel;
use Faker\Provider\uk_UA\Text;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly FileUploader $fileUploader,
        private readonly Kernel $kernel,
    ) {
    }

    public function load(ObjectManager $manager)
    {
        gc_collect_cycles();

        $faker = Factory::create('uk_UA');
        $faker->addProvider(new Text($faker));
        $faker->addProvider(new PicsumPhotosProvider($faker));
        $faker->addProvider(new FakerProvider($faker));

        $users = $manager->getRepository(UserEntity::class)
            ->findAll();

        /** @var CityEntity $city */
        $sessions = $manager->getRepository(SessionEntity::class)->findAll();


        foreach ($sessions as $session){
            $projects = [];
            for ($i = 1; $i <= random_int(300, 500); $i++) {

                $project = new ProjectEntity(
                    number: $i,
                    category: $faker->randomElement(Category::all()),
                    status: $faker->randomElement(ProjectStatus::values()),
                    budget: $faker->randomFloat(nbMaxDecimals: 2, min: 10000, max: 1000000),
                    name: $faker->sentence(5),
                    short: $faker->text(500),
                    description: (new MarkdownBuilder())->p($faker->sentences(random_int(300, 600), true))->getMarkdown(),
                    author: $faker->randomElement($users),
                    session: $session,
                );

                $city = $session->getCity();

                if ($faker->boolean(80)) {
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

                    $manager->flush();
                }

                $imagesDir = $this->kernel->getDataFixturesPath() . '/images';
                $images   = array_filter(scandir($imagesDir), fn($item) => is_file($imagesDir . '/' . $item));

                foreach ($faker->randomElements($images, random_int(1, 3)) as $image){
                    $uploadedFile = new UploadedFile($imagesDir . '/' . $image, 'image.png');
                    $projectImage = $this->fileUploader->uploadProjectImage($uploadedFile, $project);
                    $project->addImage($projectImage->getFileName());
                }

                if($faker->boolean(8)){
                    $project->delete();
                }

                $manager->persist($project);

                $projects[] = $project;
            }

            $manager->flush();

            foreach ($projects as $project){
                $manager->detach($project);
            }
        }
    }

    public function getDependencies(): array
    {
        return [
            SessionFixtures::class,
            UserFixtures::class,
        ];
    }
}