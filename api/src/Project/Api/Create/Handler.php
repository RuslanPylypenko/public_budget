<?php

declare(strict_types=1);

namespace App\Project\Api\Create;

use App\City\CityEntity;
use App\Http\Annotation\Authenticate;
use App\Project\Category;
use App\Project\ProjectFactory;
use App\User\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Handler extends AbstractController
{
    public function __construct(
        private readonly ProjectFactory $projectFactory,
        private readonly EntityManagerInterface $em,
    ) {
    }

    /**
     * @param CityEntity $city
     * @param UserEntity $user
     * @param Request $request
     * @param Command $command
     * @return Response
     */
    #[Route('/projects/', methods: ['POST']), Authenticate]
    public function handle(CityEntity $city, UserEntity $user, Request $request, Command $command): Response
    {

        $project = $this->projectFactory->fromUser(
            category: Category::from($command->category),
            budget: (float) $command->budget,
            name: $command->name,
            short: $command->short,
            description: $command->description,
            images: $command->images,
            user: $user,
            session: $city->getCurrentSession(),
        );

        $this->em->persist($project);
        $this->em->flush();

        return $this->json([]);
    }
}