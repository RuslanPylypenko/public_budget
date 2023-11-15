<?php

namespace App\User;

use Doctrine\ORM\Mapping;
use Webmozart\Assert\Assert;

#[Mapping\Embeddable]
class ConfirmToken
{
    #[Mapping\Column(type: 'string', nullable: true)]
    private ?string $token;

    #[Mapping\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $expiredAt;

    // ----------------------------------------

    public function __construct(string $token, \DateTime $expiredAt)
    {
        Assert::notEmpty($token);

        $this->token     = $token;
        $this->expiredAt = $expiredAt;
    }

    public function validate(string $token, \DateTime $date): void
    {
        if (!$this->isEqualTo($token)) {
            throw new \DomainException('Confirm token is invalid.');
        }
        if ($this->isExpiredTo($date)) {
            throw new \DomainException('Confirm token is expired.');
        }
    }

    private function isEqualTo(string $token): bool
    {
        return $this->token === $token;
    }

    private function isExpiredTo(\DateTime $date): bool
    {
        return $this->expiredAt <= $date;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function isEmpty(): bool
    {
        return empty($this->token);
    }
}