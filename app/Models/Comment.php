<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'name', 'title', 'comment', 'commentable_type', 'commentable_id'
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps = true;

    // jika menggunakan mass asign $attributes tidak berlaku
    protected $attributes = [
        'comment'       => 'example comment from default attributes'
    ];

    // product &
    public function commentable () : MorphTo
    {
        return $this->morphTo();
    }
}
