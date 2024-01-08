<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'id', 'name', 'description', 'is_active'
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $casts = [
        'createt_at'    => 'datetime:U'
    ];

    public $incrementing = false;

    public $timestamps = false;

    protected static function booted (): void
    {
        parent::booted();
        // static::addGlobalScope(new ActiveScope);
    }

    public function products () : HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function cheapestProduct () : HasOne
    {
        return $this->hasOne(Product::class, 'category_id', 'id')->oldest('price');
    }

    public function mostExpensiveProduct () : HasOne
    {
        return $this->hasOne(Product::class, 'category_id', 'id')->latest('price');
    }

    public function reviews () : HasManyThrough
    {
        return $this->hasManyThrough(
            Review::class, Product::class,
            'category_id', 'product_id', 'id', 'id'
        );
    }
}
