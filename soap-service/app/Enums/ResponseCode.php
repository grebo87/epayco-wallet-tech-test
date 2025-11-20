<?php

namespace App\Enums;

enum ResponseCode: string
{
    case SUCCESS = '00';
    case VALIDATION_ERROR = '01';
    case CLIENT_NOT_FOUND = '02';
    case CLIENT_ALREADY_EXISTS = '03';
    case INSUFFICIENT_BALANCE = '04';
    case SESSION_NOT_FOUND = '05';
    case TOKEN_EXPIRED = '06';
    case INTERNAL_ERROR = '07';
    case TRANSACTION_NOT_FOUND = '08';
    case PAYMENT_ALREADY_CONFIRMED = '09';
    case TOKEN_INVALID = '10';

    public function getMessage(): string
    {
        return match($this) {
            self::SUCCESS => 'Success',
            self::VALIDATION_ERROR => 'Validation error',
            self::CLIENT_NOT_FOUND => 'Client not found',
            self::CLIENT_ALREADY_EXISTS => 'Client already exists',
            self::INSUFFICIENT_BALANCE => 'Insufficient balance',
            self::SESSION_NOT_FOUND => 'Session not found',
            self::TOKEN_EXPIRED => 'Token expired',
            self::INTERNAL_ERROR => 'Internal error',
            self::TRANSACTION_NOT_FOUND => 'Transaction not found',
            self::PAYMENT_ALREADY_CONFIRMED => 'Payment already confirmed',
            self::TOKEN_INVALID => 'Token invalid',
        };
    }
}
