<?php

namespace App\Enums;

enum TransactionType: string
{
    case DEPOSIT = 'deposit';
    case PAYMENT = 'payment';
    case WITHDRAWAL = 'withdrawal';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
