<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductEnterprise extends Model
{
    protected $fillable = [
        'product_id',
        'enterprise_id',
        'product_enterprise_id',
    ];
}
