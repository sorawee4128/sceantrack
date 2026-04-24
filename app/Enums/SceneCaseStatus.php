<?php

namespace App\Enums;

enum SceneCaseStatus: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'ฉบับร่าง',
            self::SUBMITTED => 'ส่งแล้ว',
        };
    }

    public static function options(): array
    {
        return [
            self::DRAFT->value => self::DRAFT->label(),
            self::SUBMITTED->value => self::SUBMITTED->label(),
        ];
    }
}
