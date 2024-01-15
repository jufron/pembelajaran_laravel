<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public static $wrap = 'data';

    public function toArray(Request $request): array
    {
        return [
            'data' => ProductResource::collection($this->collection)
        ];
    }
}
