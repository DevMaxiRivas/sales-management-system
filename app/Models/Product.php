<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
