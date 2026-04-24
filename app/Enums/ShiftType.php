<?php

namespace App\Enums;

enum ShiftType: string
{
    case DAY = 'day';
    case NIGHT = 'night';

    public function label(): string
    {
        return match ($this) {
            self::DAY => 'กะกลางวัน (08:00 - 16:00)',
            self::NIGHT => 'กะกลางคืน (16:00 - 08:00)',
        };
    }

    public static function options(): array
    {
        return [
            self::DAY->value => self::DAY->label(),
            self::NIGHT->value => self::NIGHT->label(),
        ];
    }
}
