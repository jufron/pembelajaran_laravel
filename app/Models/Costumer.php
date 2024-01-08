<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Factories\HasFactory,
    Model,
    Relations\HasOne,
    Relations\HasMany,
    Relations\HasOneThrough
};
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Costumer extends Model
{
    use HasFactory;

    protected $table = 'costumers';

    protected $with = ['image', 'wallet'];

    protected $fillable = [
        'id', 'name', 'email'
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = true;

    public function wallet (): HasOne
    {
        return $this->hasOne(Wallet::class, 'costumer_id', 'id');
    }

    public function virtualAcount () : HasOneThrough
    {
        return $this->hasOneThrough(VirtualAcount::class, Wallet::class,
            'costumer_id', 'wallet_id', 'id', 'id'
        );
    }

    public function reviews () : HasMany
    {
        return $this->hasMany(Review::class, 'costumer_id', 'id');
    }

    public function whereLikesNotNull () : BelongsToMany
    {
        return $this->belongsToMany(
            Product::class, 'table_costumers_likes_products', 'costumer_id', 'product_id'
        )->wherePivotNotNull('created_at')
         ->orderByPivot('created_at', 'asc');
    }

    public function likeProducts () : BelongsToMany
    {
        return $this->belongsToMany(
            Product::class, 'table_costumers_likes_products', 'costumer_id', 'product_id'
        )->as('likes')
         ->withTimestamps();
    }

    public function image () : MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
