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
    public const PROJECT_STATUSES = [
        Project::STATUS_MODERATION,
        Project::STATUS_AUTHOR_EDIT,
        Project::STATUS_REVIEW,
        Project::STATUS_REJECTED,
        Project::STATUS_APPROVED,
        Project::STATUS_VOTING,
        Project::STATUS_REJECTED_FINAL,
        Project::STATUS_AWAIT,
        Project::STATUS_PARTICIPANT,
        Project::STATUS_WINNER,
        Project::STATUS_IMPLEMENTATION,
        Project::STATUS_IMPLEMENTATION_FAILED,
        Project::STATUS_FINISHED,
        Project::STATUS_DELETED,
    ];

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
            status     : Project::STATUS_AWAIT,
            budget     : $budget,
            name       : $name,
            short      : $short,
            description: $description,
            author     : $user,
            session    : $session,
        );

        foreach ($images as $image){
            /** @var UploadedFile $image */
            $this->fileUploader->uploadProjectImage($image, $project);
        }

        return $project;
    }
}