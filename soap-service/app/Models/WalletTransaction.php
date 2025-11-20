<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'description',
        'session_id',
        'token',
        'status',
        'confirmed',
        'token_expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'token_expires_at' => 'datetime',
        'confirmed' => 'boolean',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
