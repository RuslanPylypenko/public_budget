<?php

declare(strict_types=1);

namespace App\Project\Api\Update;

use App\City\CityEntity;
use App\Http\Annotation\Authenticate;
use App\Project\Api\Create\Command;
use App\Project\Category;
use App\Project\ProjectEntity;
use App\Project\ProjectFactory;
use App\Project\Uploader\FileUploader;
use App\User\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Handler extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly FileUploader $fileUploader,
    ) {
    }


    #[Route('/projects/{number}', methods: ['PATCH']), Authenticate]
    public function handle(CityEntity $city, int $number, Command $command): Response
    {
        /** @var ProjectEntity $project */
        $project = $this->em->getRepository(ProjectEntity::class)->get($city->getCurrentSession(), $number);

        $project->setCategory(Category::from($command->category));
        $project->setBudget((float) $command->budget);
        $project->setName($command->name);
        $project->setShort($command->short);
        $project->setDescription($command->description);

        $this->fileUploader->updateProjectImages($project, $command->images);

        $this->em->persist($project);
        $this->em->flush();

        return $this->json([]);
    }
}