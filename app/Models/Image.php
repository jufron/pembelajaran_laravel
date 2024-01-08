<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';

    protected $fillable = [
        'url', 'imageable_id', 'imageable_type'
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps = true;

    public function imageable () : MorphTo
    {
        return $this->morphTo();
    }
}
