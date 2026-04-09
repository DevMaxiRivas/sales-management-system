<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'bar_code',
        'name',
        'stock',
        'min_stock',
        'created_at',
        'updated_at',
    ];

    public function enterprises(): BelongsToMany
    {
        return $this->belongsToMany(Enterprise::class, 'product_enterprise', 'product_id', 'enterprise_id');
    }
}
