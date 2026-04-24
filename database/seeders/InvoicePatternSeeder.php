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
                "pattern" => '/^\*{0,1}[0-9]{13}$/',
                "created_at" => "2026-04-15 19:35:44",
                "updated_at" => "2026-04-20 18:49:00",
                "deleted_at" => null
            ),
            array(
                "id" => 5,
                "enterprise_id" => 3,
                "type" => 0,
                "pattern" => '/^\[[0-9]{5}\]$/',
                "created_at" => "2026-04-15 21:40:53",
                "updated_at" => "2026-04-15 21:40:53",
                "deleted_at" => null
            ),
            array(
                "id" => 6,
                "enterprise_id" => 1,
                "type" => 0,
                "pattern" => '/^[0-9]{7}$/',
                "created_at" => "2026-04-15 22:31:41",
                "updated_at" => "2026-04-15 22:31:50",
                "deleted_at" => null
            ),
            array(
                "id" => 7,
                "enterprise_id" => 2,
                "type" => 1,
                "pattern" => "/[\d]+(\d{3})/",
                "created_at" => "2026-04-19 18:19:05",
                "updated_at" => "2026-04-19 18:19:05",
                "deleted_at" => null
            ),
            array(
                "id" => 8,
                "enterprise_id" => 3,
                "type" => 1,
                "pattern" => "/[\d]+(\d{4})/",
                "created_at" => "2026-04-19 18:19:26",
                "updated_at" => "2026-04-19 18:19:26",
                "deleted_at" => null
            )
        );

        InvoicePattern::insert($invoice_patterns);
    }
}
