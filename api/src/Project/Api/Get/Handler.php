<?php

declare(strict_types=1);

namespace App\Project\Api\Get;

use App\Api\Exception\ApiException;
use App\Project\Api\DataBuilder;
use App\Project\ProjectEntity as Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Handler extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly DataBuilder $dataBuilder,
    ) {
    }

    #[Route('/projects/{id}', methods: ['GET'])]
    public function handle(int $id): Response
    {
        if (null === $project = $this->em->getRepository(Project::class)->find($id)) {
            throw new ApiException('Project not found');
        }

        return $this->json(
            $this->dataBuilder->project($project, true)
        );
    }
}