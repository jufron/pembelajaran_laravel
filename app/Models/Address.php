<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Address extends Model
{
    use HasFactory;

    protected $talbe = 'addresses';

    protected $fillable = [
        'street',
        'rt',
        'rw',
        'city',
        'province',
        'country',
        'postal_code',
        "contact_id"
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps = true;

    public function contact () : HasOne
    {
        return $this->hasOne(Contact::class, 'contact_id', 'id');
    }
}
