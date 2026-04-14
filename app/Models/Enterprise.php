<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enterprise extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'tax_id',
        'created_at',
        'updated_at',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_enterprise', 'enterprise_id', 'product_id')
            ->withPivot('product_enterprise_id');
    }

    public function enterprise_products(): HasMany
    {
        return $this->hasMany(ProductEnterprise::class);
    }

    public function invoice_patterns(): HasMany
    {
        return $this->hasMany(InvoicePattern::class);
    }
}
