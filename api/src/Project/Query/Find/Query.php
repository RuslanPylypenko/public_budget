<?php

declare(strict_types=1);

namespace App\Project\Query\Find;

use App\Api\InputInterface;
use App\Common\CQRS\IQuery;
use Symfony\Component\Validator\Constraints as Assert;

class Query implements IQuery, InputInterface
{
    #[Assert\NotBlank(allowNull: true)]
    public ?string $search = null;

    public int|null $cityId = null;

    #[Assert\NotBlank(allowNull: false), Assert\Collection(
        fields: [
            'limit' => new Assert\Required([
                new Assert\NotBlank,
                new Assert\Type('integer'),
            ]),
            'offset' => new Assert\Required([
                    new Assert\NotBlank,
                    new Assert\Type('integer'),
                ]
            ),
        ],
    )]
    public array $result = [
        'limit' => null,
        'offset' => null,
    ];
}
