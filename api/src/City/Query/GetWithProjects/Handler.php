<?php

declare(strict_types=1);

namespace App\City\Query\GetWithProjects;

use App\City\CityEntity;
use App\Common\CQRS\QueryHandler;
use App\Session\SessionEntity;
use App\Utils\DateTime;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;

readonly class Handler implements QueryHandler
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function __invoke(Query $query): array
    {
        $qb = $this->em
            ->getRepository(CityEntity::class)
            ->createQueryBuilder('c')
            ->select(['c.name as cityName', 'c.mainTitle', 'c.mainText', 'c.lat', 'c.lon'])
            ->addSelect(['s.name as sessionName'])
            ->addSelect(['ss.name as stageName', 'ss.isEnable', 'ss.startDate', 'ss.endDate'])
            ->join('c.sessions', 's')
            ->join('s.stages', 'ss');

        $qb
            ->where($qb->expr()->eq('c.id', ':id'))
            ->andWhere($qb->expr()->lte('s.startDate',  ':startDate'))
            ->andWhere($qb->expr()->gte('s.endDate', ':endDate'))
            ->setParameter('id', $query->cityId)
            ->setParameter('startDate', DateTime::current())
            ->setParameter('endDate', DateTime::current());

        $result = [];

        foreach ($qb->getQuery()->getArrayResult() as $row) {
            $result['name']      = $row['cityName'];
            $result['mainTitle'] = $row['mainTitle'];
            $result['mainText']  = $row['mainText'];
            $result['lat']       = $row['lat'];
            $result['lon']       = $row['lon'];
            $result['currentSession']['name']     = $row['sessionName'];
            $result['currentSession']['stages'][] = [
                'name'      => $row['stageName'],
                'isEnable'  => $row['isEnable'],
                'startDate' => $row['startDate'],
                'endDate'   => $row['endDate'],
            ];
        }
        return $result;
    }
}
