<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Client extends Model
{
    protected $fillable = [
        'document',
        'name',
        'email',
        'phone',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }
}
