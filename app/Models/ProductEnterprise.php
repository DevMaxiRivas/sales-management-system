<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductEnterprise extends Pivot
{
    protected $fillable = [
        'product_id',
        'enterprise_id',
        'product_enterprise_id',
    ];
}
