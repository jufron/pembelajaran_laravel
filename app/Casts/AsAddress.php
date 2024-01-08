<?php

namespace App\Casts;

use App\Models\Address;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class AsAddress implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): Address
    {
        if ($value === null) return null;

        $address = explode(', ', $value);
        return new Address(
            street:     $address[0],
            city:       $address[1],
            country:    $address[2],
            postalCode: $address[3]
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) return null;
        return "{$value->street}, {$value->city}, {$value->country}, {$value->postalCode}";
    }
}
