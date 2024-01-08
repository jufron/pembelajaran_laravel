<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tags';

    protected $fillable = [
        'id', 'name'
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = true;

    public function products () : MorphToMany
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }

    public function vochers () : MorphToMany
    {
        return $this->morphedByMany(Vocher::class, 'taggable');
    }

}
