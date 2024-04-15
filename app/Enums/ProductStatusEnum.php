<?php

namespace App\Enums;

enum ProductStatusEnum: string
{
    case InStock = 'in stock';
    case SoldOut = 'sold out';
    case ComingSoon = 'coming soon';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function valuesArray():array
    {
        return array_combine(self::values(), self::values());
    }
}
