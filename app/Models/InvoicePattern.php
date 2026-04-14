<?php

namespace App\Models;

use App\Enums\InvoicePatern\PatternInvoiceTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoicePattern extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'entterprise_id',
        'type',
        'pattern',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'type' => PatternInvoiceTypeEnum::class,
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    public function enterprise(): BelongsTo
    {
        return $this->belongsTo(Enterprise::class);
    }
}
