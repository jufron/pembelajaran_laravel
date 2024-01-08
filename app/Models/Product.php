<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'id', 'name', 'description', 'price', 'stock', 'category_id'
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected $hidden = ['category_id'];

    public $incrementing = false;

    public $timestamps = false;

    public function category (): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function reviews () : HasMany
    {
        return $this->hasMany(Review::class, 'product_id', 'id');
    }

    public function likeByeCostumers () : BelongsToMany
    {
        return $this->belongsToMany(
            Costumer::class, 'table_costumers_likes_products', 'product_id', 'costumer_id'
        )->as('likes')
         ->withTimestamps();
    }

    public function image () : MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function comments () : MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function latestComment () : MorphOne
    {
        return $this->morphOne(Comment::class, 'commentable')
                    ->latest('created_at');
    }

    public function olddestComment () : MorphOne
    {
        return $this->morphOne(Comment::class, 'commentable')
                    ->oldest('created_at');
    }

    public function tags () : MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
