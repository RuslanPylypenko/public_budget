<?php

namespace App\Session;

use App\Category\CategoryEntity as Category;
use App\City\CityEntity as City;
use App\Session\Api\Create\Command;
use App\Session\Stage\StageEntity as Stage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SessionFactory
{
    public function __construct(
      private readonly EntityManagerInterface $em,
    )
    {
    }

    public function create(Command $command): SessionEntity
    {
        $city = $this->em->getRepository(City::class)->findOneBy([
            'id' => $command->cityId,
        ]);

        if (null === $city) {
            throw new NotFoundHttpException();
        }

        $session = new SessionEntity(
            name: $command->name,
            description: $command->description,
            city: $city,
            isDraft: $command->isDraft,
        );

        /** @var Category[] $categories */
        $categories = $this->em->getRepository(Category::class)->findBy([
            'id' => $command->categories,
        ]);

        foreach ($categories as $category) {
            $session->addCategory($category);
        }

        foreach ($command->stages as $stageData) {
            $stage = new Stage(
                name:     $stageData['name'],
                start:    $stageData['start'],
                end:      $stageData['end'],
                isActive: $stageData['is_active'],
            );

            $session->addStage($stage);
        }





        return $session;
    }
}