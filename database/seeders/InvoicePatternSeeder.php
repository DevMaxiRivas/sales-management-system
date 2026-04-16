<?php

namespace Database\Seeders;

use App\Models\InvoicePattern;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoicePatternSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $invoice_patterns = array(
            array(
                "id" => 4,
                "enterprise_id" => 2,
                "type" => 0,
                "pattern" => "\/^\\*{0,1}[0-9]{13}$\/",
                "created_at" => "2026-04-15 19:35:44",
                "updated_at" => "2026-04-15 19:35:44",
                "deleted_at" => null
            ),
            array(
                "id" => 5,
                "enterprise_id" => 3,
                "type" => 0,
                "pattern" => "\/^\\[[0-9]{5}\\]$\/",
                "created_at" => "2026-04-15 21:40:53",
                "updated_at" => "2026-04-15 21:40:53",
                "deleted_at" => null
            ),
            array(
                "id" => 6,
                "enterprise_id" => 1,
                "type" => 0,
                "pattern" => "\/^[0-9]{7}$\/",
                "created_at" => "2026-04-15 22:31:41",
                "updated_at" => "2026-04-15 22:31:50",
                "deleted_at" => null
            )
        );

        InvoicePattern::insert($invoice_patterns);
    }
}
