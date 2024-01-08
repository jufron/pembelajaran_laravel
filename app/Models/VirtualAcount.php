<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VirtualAcount extends Model
{
    use HasFactory;

    protected $table = 'virtual_acounts';

    protected $fillable = [
        'bank', 'va_number', 'wallet_id'
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps = true;

    public function wallet () : BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }
}
