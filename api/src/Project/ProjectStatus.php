<?php

declare(strict_types=1);

namespace App\Project;

enum ProjectStatus: string
{
    case AUTHOR_EDIT           = "author_edit";
    case REVIEW                = "review";
    case REJECTED              = "rejected";
    case APPROVED              = "approved";
    case VOTING                = "voting";
    case REJECTED_FINAL        = "rejected_final";
    case AWAIT                 = "await";
    case PARTICIPANT           = "participant";
    case WINNER                = "winner";
    case IMPLEMENTATION        = "implementation";
    case IMPLEMENTATION_FAILED = "implementation_failed";
    case FINISHED              = "finished";
    case DELETED               = "deleted";

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }

    public function title(): string
    {
        return ucfirst(str_replace('_', ' ', $this->value));
    }
}
