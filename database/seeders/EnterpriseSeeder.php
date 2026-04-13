<?php

namespace Database\Seeders;

use App\Models\Enterprise;
use App\Models\ProductEnterprise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnterpriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enterprises = array(
            array(
                "id" => 1,
                "name" => "Supermayorista Vital",
                "tax_id" => "30612865333",
                "created_at" => "2026-04-07 21:56:22",
                "updated_at" => "2026-04-07 21:56:22",
                "deleted_at" => null
            ),
        );

        Enterprise::insert($enterprises);

        $product_enterprise = array(
            array(
                "product_id" => 1,
                "enterprise_id" => 1,
                "product_enterprise_id" => 3242342342
            )
        );

        ProductEnterprise::insert($product_enterprise);
    }
}
