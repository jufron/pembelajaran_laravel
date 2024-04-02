<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public static $wrap = 'value';

    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'category'      => new CategorySimpleResource($this->whenLoaded('category')),
            'price'         => $this->price,
            'is_expensive'  => $this->when($this->price >= 200000, true, false),
            'stock'         => $this->stock,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at
        ];
    }

    public function withResponse(Request $request, JsonResponse $response)
    {
        $response->header('X-header', 'ini adalah x header');
    }
}
