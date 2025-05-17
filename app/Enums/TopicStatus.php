<?php

namespace App\Enums;

enum TopicStatus: int
{
    case OPENED = 1;
    case CLOSED = 0;

    /**
     * Get all values as an array
     *
     * @return array<int>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Check if the status is opened
     */
    public function isOpened(): bool
    {
        return $this === self::OPENED;
    }

    /**
     * Check if the status is closed
     */
    public function isClosed(): bool
    {
        return $this === self::CLOSED;
    }
}
