<?php

declare(strict_types=1);

namespace App\Project\Command\Create;

use App\Api\InputInterface;
use App\City\CityEntity;
use App\Common\CQRS\ICommand;
use App\Common\CQRS\IQuery;
use App\Project\Category;
use App\User\UserEntity;
use Symfony\Component\Validator\Constraints as Assert;

class Command implements InputInterface, ICommand
{
    #[Assert\NotBlank, Assert\Choice(callback: 'category')]
    public string $category;

    #[Assert\NotBlank, Assert\Range(min: 0)]
    public float|string $budget;

    #[Assert\NotBlank, Assert\Length(min: 1, max: 250)]
    public string $name;

    #[Assert\NotBlank, Assert\Length(min: 1, max: 900)]
    public string $short;

    #[Assert\NotBlank, Assert\Length(min: 1, max: 64000)]
    public string $description;

    #[Assert\All([
        new Assert\Image(
            maxSize: 134217728, // 128 megabytes in bytes,
            mimeTypes: ["image/jpeg", "image/jpg", "image/png"],
            minWidth: 500,
            minHeight: 500,
        ),
    ])]
    public array $images;

    private ?UserEntity $user = null;

    private ?CityEntity $city = null;

    public function category(): array
    {
        return array_column(Category::cases(), 'value');
    }

    public function getUser(): ?UserEntity
    {
        return $this->user;
    }

    public function setUser(UserEntity $user): void
    {
        $this->user = $user;
    }

    public function getCity(): ?CityEntity
    {
        return $this->city;
    }

    public function setCity(CityEntity $city): void
    {
        $this->city = $city;
    }
}