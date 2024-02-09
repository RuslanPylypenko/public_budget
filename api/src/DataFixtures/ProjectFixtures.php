<?php

namespace App\DataFixtures;

use App\City\CityEntity;
use App\Project\Address\AddressEntity;
use App\Project\Category;
use App\Project\ProjectEntity;
use App\Project\ProjectFactory;
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
        $faker = Factory::create('uk_UA');
        $faker->addProvider(new Text($faker));
        $faker->addProvider(new PicsumPhotosProvider($faker));
        $faker->addProvider(new FakerProvider($faker));

        $users = $manager->getRepository(UserEntity::class)
            ->findAll();

        /** @var CityEntity $city */
        $sessions = $manager->getRepository(SessionEntity::class)->findAll();


        foreach ($sessions as $session){
            for ($i = 1; $i <= 200; $i++) {

                $project = new ProjectEntity(
                    number: $i,
                    category: $faker->randomElement(Category::all()),
                    status: $faker->randomElement(ProjectFactory::PROJECT_STATUSES),
                    budget: $faker->randomFloat(nbMaxDecimals: 2, min: 10000, max: 1000000),
                    name: $faker->sentence(5),
                    short: $faker->text(200),
                    description: $faker->markdown() . (new MarkdownBuilder())->p($faker->sentences(20, true))->getMarkdown(),
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

                $imagesDir = $this->kernel->getDataFixturesPath() . '/images';
                $images   = array_filter(scandir($imagesDir), fn($item) => is_file($imagesDir . '/' . $item));

                foreach ($faker->randomElements($images, random_int(1, 3)) as $image){
                    $uploadedFile = new UploadedFile($imagesDir . '/' . $image, 'image.png');
                    $projectImage = $this->fileUploader->uploadProjectImage($uploadedFile, $project);
                    $project->addImage($projectImage->getFileName());
                }

                $manager->persist($project);

                if($i % 500 === 0) {
                    $manager->flush();
                    $manager->clear();
                }
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