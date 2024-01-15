<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDebugResource extends JsonResource
{
    // public $additional = [
    //     'author'    => 'jufron tamo ama'
    // ];

    public static $wrap = 'data';

    public function toArray(Request $request): array
    {
        return [
            'author'        => 'jufron tamo ama',
            'server_time'   => now()->toDateTimeString(),
            'data'          => [
                'id'    => $this->id,
                'name'  => $this->name,
                'price' => $this->price
            ]
        ];
    }
}
