<?php

namespace App\City\Api;

use App\City\CityEntity;
use App\Project\Api\DataBuilder as ProjectsDataBuilder;
use App\Project\ProjectEntity as Project;
use App\Session\StageEntity;
use Doctrine\ORM\EntityManagerInterface;

class DataBuilder
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ProjectsDataBuilder $projectsDataBuilder,
    ) {
    }

    public function city(CityEntity $city): array
    {
        $session = $city->getCurrentSession();

        $qb = $this->em->getRepository(Project::class)->createQueryBuilder('p');
        $qb
            ->where($qb->expr()->eq('p.session', ':session'))
            ->setMaxResults(5)
            ->orderBy('RAND()')
            ->setParameter('session', $session);

        $projects = $qb->getQuery()->getResult();
        return [
            'name'           => $city->getName(),
            'mainTitle'      => $city->getMainTitle(),
            'mainText'       => $city->getMainText(),
            'currentSession' => [
                    'name' => $session->getName(),
                    'stages' => array_values(
                        array_map(static fn(StageEntity $stage) => $stage->toArray(), $session->getStages()->toArray())
                    ),
                ],
            'projects' => $this->projectsDataBuilder->projects($projects),
            'lat'            => $city->getLat(),
            'lon'            => $city->getLon()
        ];
    }
}