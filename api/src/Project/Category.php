<?php

declare(strict_types=1);

namespace App\Project;

enum Category: string
{
    case EDUCATION = 'education';
    case SPORT = 'sport';
    case INFRASTRUCTURE = 'infrastructure';
    case MEDICINE = 'medicine';
    case TRAVELING = 'traveling';
    case CULTURE = 'culture';
    case OTHER = 'other';


    /**
     * @return Category[]
     */
    public static function all(): array
    {
        return [
            self::EDUCATION,
            self::SPORT,
            self::INFRASTRUCTURE,
            self::MEDICINE,
            self::CULTURE,
            self::OTHER,
        ];
    }

    public function title(): string
    {
        return ucfirst($this->value);
    }
}