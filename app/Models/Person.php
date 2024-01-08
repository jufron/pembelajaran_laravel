<?php

namespace App\Models;

use App\Casts\AsAddress;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';

    protected $fillable = ['first_name', 'last_name', 'is_admin', 'address'];

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'is_admin'      => 'boolean',
        'address'       => AsAddress::class
    ];

    public $incrementing = true;

    public $timestamps = true;

    protected function fullName () : Attribute
    {
        return Attribute::make(
            get: fn () : string => $this->first_name . ' ' . $this->last_name,
            set: function (string $value) : array {
                $names = explode(' ', $value);
                return [
                    'first_name'    => $names[0],
                    'last_name'     => $names[1]
                ];
            }
        );
    }

    protected function firstName () : Attribute
    {
        return Attribute::make(
            get: fn (string $value) : string => strtoupper($value),
            set: fn (string $value) : array => ['first_name' => strtoupper($value)]
        );
    }
}
