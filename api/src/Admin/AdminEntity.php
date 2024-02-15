<?php

declare(strict_types=1);

namespace App\Admin;

use App\Auth\AuthEntitySuperclass;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;

#[Mapping\Entity]
#[Mapping\Table(name: 'admin')]
#[Mapping\HasLifecycleCallbacks]
class AdminEntity extends AuthEntitySuperclass
{
    #[Mapping\Column(name: 'name', type: Types::STRING, length: 255)]
    private string $name;

    #[Mapping\Column(name: 'surname', type: Types::STRING, length: 255)]
    private string $surname;

    #[Mapping\Column(name: 'is_active', type: Types::BOOLEAN, options: ['default' => true])]
    private bool $isActive = true;

    public function __construct(
        string $email,
        string $name,
        string $surname,
        bool $isActive = true,
    ) {
        parent::__construct($email);

        $this->name     = $name;
        $this->surname  = $surname;
        $this->isActive = $isActive;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->getEmail(),
            'surname' => $this->surname,
        ];
    }
}