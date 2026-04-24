<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class InvoiceProduct extends Pivot
{
    public $table = 'invoice_product';
}
