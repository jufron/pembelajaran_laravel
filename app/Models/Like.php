<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Like extends Pivot
{
    protected $table = 'table_costumers_likes_products';

    protected $foreignKey = 'costumer_id';

    protected $relatedKey = 'product_id';

    public $incrementing = false;

    public function cstumer () : BelongsTo
    {
        return $this->belongsTo(cstumer::class, 'costumer_id', 'id');
    }

    public function product () : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
