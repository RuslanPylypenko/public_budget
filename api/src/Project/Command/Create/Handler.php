<?php

declare(strict_types=1);

namespace App\Project\Command\Create;

use App\City\CityEntity;
use App\Common\CQRS\CommandHandler;
use App\Http\Annotation\Authenticate;
use App\Project\Category;
use App\Project\ProjectFactory;
use App\User\UserEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

readonly class Handler implements CommandHandler
{
    public function __construct(
        private ProjectFactory $projectFactory,
        private EntityManagerInterface $em,
    ) {
    }

    public function __invoke(Command $command): void
    {
        $project = $this->projectFactory->fromUser(
            category: Category::from($command->category),
            budget:(float) $command->budget,
            name: $command->name,
            short: $command->short,
            description: $command->description,
            images: $command->images,
            user: $command->getUser(),
            session: $command->getCity()->getCurrentSession(),
        );

        $this->em->persist($project);
        $this->em->flush();
    }
}