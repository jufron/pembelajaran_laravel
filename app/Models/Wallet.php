<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Wallet extends Model
{
    use HasFactory;

    protected $table = 'wallets';

    protected $fillable = [
        'costumer_id', 'amount'
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps = true;

    public function costumer (): BelongsTo
    {
        return $this->belongsTo(Costumer::class, 'costumer_id', 'id');
    }

    public function virtualAcount () : HasOne
    {
        return $this->hasOne(VirtualAcount::class, 'wallet_id', 'id');
    }
}
