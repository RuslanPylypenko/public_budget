<?php

namespace App\Project\Api\Find;

use App\Project\Api\DataBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Handler extends AbstractController
{
    public function __construct(
        private readonly DataBuilder $dataBuilder,
        private readonly RepositoryFactory $repositoryFactory,
    ) {
    }

    #[Route('/projects/find/', methods: ['GET'])]
    public function handle(Command $command): Response
    {
        $repository = $this->repositoryFactory->fromInput($command);

        return $this->json([
            'list'  => $this->dataBuilder->projects($repository->result()),
            'total' => $repository->total()
        ]);
    }
}