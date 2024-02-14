<?php

declare(strict_types=1);

namespace App\Project\Command\Update;

use App\Project\Category;
use App\Project\ProjectEntity;
use App\Project\Uploader\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

readonly class UpdateProjectHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private FileUploader $fileUploader,
    ) {
    }

    public function handle(UpdateProjectCommand $command): ProjectEntity
    {
        /** @var ProjectEntity $project */
        $project = $this->em
            ->getRepository(ProjectEntity::class)
            ->findOneBy([
                'session' => $command->getCity()->getCurrentSession(),
                'number'  => $command->getNumber()
            ]);

        if (!$project) {
            throw new DomainException('Project is not found.');
        }

        $project->setCategory(Category::from($command->category));
        $project->setBudget((float) $command->budget);
        $project->setName($command->name);
        $project->setShort($command->short);
        $project->setDescription($command->description);

        $this->fileUploader->updateProjectImages($project, $command->images);

        $this->em->persist($project);
        $this->em->flush();

        return $project;
    }
}