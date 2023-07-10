<?php

namespace App\Session\Api\Create;

use App\Api\InputInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

class Command implements InputInterface
{
    #[NotBlank, Type('integer')]
    public int $cityId;

    #[NotBlank, Length(min: 1)]
    public string $name;

    #[NotBlank, Length(min: 1)]
    public string $description;

    #[NotBlank, Type('bool')]
    public bool $isDraft;

    public array $categories = [];

    #[NotNull, All(constraints: [
        new Collection(fields: [
            "name"  => [new NotBlank(), new Length(min: 1), new Type('string')],
            "start" => [new NotBlank(), new Type('date')],
            "end"   => [new NotBlank(), new Type('date')],
            "is_active" => [new NotBlank(), new Type('bool')],
        ])
    ])]
    public array $stages;

    #[NotNull, All(constraints: [
        new Collection(fields: [
            "by_type" => [new Type('string')],
            "category_id" => [new Type('integer')],
            "total_budget" => [new NotBlank(), new Type('integer'), new GreaterThan(0)],
            "min_budget"   => [new NotBlank(), new Type('integer'), new GreaterThan(0)],
            "max_budget"   => [new NotBlank(), new Type('integer'), new GreaterThan(0)],
            "min_votes"    => [new NotBlank(), new Type('integer'), new GreaterThan(0)],
        ])
    ])]
    public array $restrictionsBudget = [];

    #[NotNull, All(constraints: [
        new Collection(fields: [
            "category_id" => [new Type('integer')],
            "max_projects_count"   => [new NotBlank(), new Type('integer'), new GreaterThan(0)],
        ])
    ])]
    public array $restrictionsProjectsCount;

    #[NotNull, All(constraints: [
        new Collection(fields: [
            "type" => [new NotBlank(), new Length(min: 1), new Type('string')],
            "residents_only" => [new NotBlank(), new Type('bool')],
            "email_required" => [new NotBlank(), new Type('bool')],
            "phone_required" => [new NotBlank(), new Type('bool')],
            "min_age" => [new NotBlank(), new Type('integer'), new GreaterThan(0)],
        ])
    ])]
    public array $restrictionProjectAuthor;

    #[NotNull, All(constraints: [
        new Collection(fields: [
            "type" => [new NotBlank(), new Length(min: 1), new Type('string')],
            "residents_only" => [new NotBlank(), new Type('bool')],
            "email_required" => [new NotBlank(), new Type('bool')],
            "phone_required" => [new NotBlank(), new Type('bool')],
            "min_age" => [new NotBlank(), new Type('integer'), new GreaterThan(0)],
        ])
    ])]
    public array $restrictionProjectVote;


    #[IsTrue(message: 'Restriction budget is not valid')]
    public function isRestrictionsBudget(): bool
    {
        foreach ($this->restrictionsBudget as $restriction){
            if ($restriction['min_budget'] < $restriction['max_budget']){
                return false;
            }
        }

        return true;
    }


    #[IsTrue(message: 'Categories is not valid')]
    public function isCategory(): bool
    {
        foreach ($this->categories as $element) {
            if (!is_numeric($element)) {
                return false;
            }
        }

        return true;
    }

}
