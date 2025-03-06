<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case ACTIVE = 'active';
    case CANCELLED = 'cancelled';
    case PENDING = 'pending';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::CANCELLED => 'Cancelled',
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
        };
    }
}
