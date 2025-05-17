<?php

namespace App\Enums;

enum Gender: int
{
    case UNKNOWN = -1;
    case FEMALE = 0;
    case MALE = 1;

    /**
     * Get a human-readable label for the gender
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::UNKNOWN => 'Unknown',
            self::FEMALE => 'Female',
            self::MALE => 'Male',
        };
    }
}
