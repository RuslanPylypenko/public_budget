<?php

declare(strict_types=1);

namespace App\Project\Api\Create;

use App\Api\InputInterface;
use App\Project\Category;
use Symfony\Component\Validator\Constraints as Assert;

class Command implements InputInterface
{
    #[Assert\Choice(callback: 'category')]
    public string $category;

    #[Assert\NotBlank, Assert\Range(min: 0)]
    public string $budget;

    #[Assert\NotBlank, Assert\Length(min: 1, max: 250)]
    public string $name;

    #Assert\[NotBlank, Length(min: 1, max: 900)]
    public string $short;

    #[Assert\NotBlank, Assert\Length(min: 1, max: 64000)]
    public string $description;

    #[Assert\NotBlank, Assert\All([
        new Assert\Image(
            maxSize: 134217728, // 128 megabytes in bytes,
            mimeTypes: ["image/jpeg", "image/jpg", "image/png"],
            minWidth: 500,
            minHeight: 500,
        ),
    ])]
    public array $images;

    public function category(): array
    {
        return array_column(Category::cases(), 'value');
    }
}