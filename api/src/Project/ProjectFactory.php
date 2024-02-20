<?php

namespace App\Project;

use App\Project\ProjectEntity as Project;
use App\Project\Uploader\FileUploader;
use App\Session\SessionEntity;
use App\User\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProjectFactory
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly FileUploader $fileUploader,
    ) {
    }

    public function fromUser(
        Category $category,
        float $budget,
        string $name,
        string $short,
        string $description,
        array $images,
        UserEntity $user,
        SessionEntity $session,
    ): Project
    {
        $qb = $this->em->getRepository(Project::class)->createQueryBuilder('p');
        $qb->select('MAX(p.number) AS max_number')
            ->where($qb->expr()->eq('p.session', ':session'))
            ->setParameter('session', $session);
        $result = $qb->getQuery()->getOneOrNullResult();
        $max = $result['max_number'] ?: 0;

        $project = new Project(
            number     : $max + 1,
            category   : $category,
            status     : ProjectStatus::AWAIT->value,
            budget     : $budget,
            name       : $name,
            short      : $short,
            description: $description,
            author     : $user,
            session    : $session,
        );

        foreach ($images as $image){
            /** @var UploadedFile $image */
            $projectImage = $this->fileUploader->uploadProjectImage($image, $project);
            $project->addImage($projectImage->getFileName());
        }

        return $project;
    }
}