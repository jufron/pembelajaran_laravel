<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $table = 'todos';

    protected $fillable = ['todolist'];

    protected $primaryKey = 'id';

    protected $pkeyType = 'int';

    public $incrementing = 'id';

    public $timestamps = true;
}
