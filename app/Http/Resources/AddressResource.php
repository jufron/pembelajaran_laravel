<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'street'        => $this->street,
            'rt'            => $this->rt,
            'rw'            => $this->rw,
            'city'          => $this->city,
            'province'      => $this->province,
            'country'       => $this->country,
            'postal_code'   => $this->postal_code
        ];
    }
}
