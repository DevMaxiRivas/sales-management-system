<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    protected $fillable = [
        'name',
        'tax_id',
        'created_at',
        'updated_at',
    ];
}
