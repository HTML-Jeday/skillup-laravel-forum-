<?php

namespace App\Enums;

enum Role: string
{
    case USER = 'user';
    case MODERATOR = 'moderator';
    case ADMIN = 'admin';

    /**
     * Get a human-readable label for the role
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::USER => 'User',
            self::MODERATOR => 'Moderator',
            self::ADMIN => 'Administrator',
        };
    }

    /**
     * Check if the role has at least the specified permission level
     *
     * @param Role $role
     * @return bool
     */
    public function hasPermissionLevel(Role $role): bool
    {
        $levels = [
            self::USER->value => 1,
            self::MODERATOR->value => 2,
            self::ADMIN->value => 3,
        ];

        return $levels[$this->value] >= $levels[$role->value];
    }

    /**
     * Get all available roles as an array
     *
     * @return array
     */
    public static function getValues(): array
    {
        return [
            self::USER->value,
            self::MODERATOR->value, 
            self::ADMIN->value
        ];
    }
}
