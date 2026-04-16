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
                "name" => "SUPERMAYORISTA VITAL",
                "tax_id" => "30612865333",
                "created_at" => "2026-04-07 21:56:22",
                "updated_at" => "2026-04-14 19:57:58",
                "deleted_at" => null
            ),
            array(
                "id" => 2,
                "name" => "TORNADO",
                "tax_id" => "30710589018",
                "created_at" => "2026-04-15 19:35:13",
                "updated_at" => "2026-04-15 19:35:13",
                "deleted_at" => null
            ),
            array(
                "id" => 3,
                "name" => "MAXICOMODIN",
                "tax_id" => "30578411174",
                "created_at" => "2026-04-15 21:40:19",
                "updated_at" => "2026-04-15 21:40:19",
                "deleted_at" => null
            )
        );

        Enterprise::insert($enterprises);

        $product_enterprise = array(
            array(
                "product_id" => 1,
                "enterprise_id" => 2,
                "product_enterprise_id" => 7790250015840
            ),
            array(
                "product_id" => 1,
                "enterprise_id" => 3,
                "product_enterprise_id" => 37252
            ),
            array(
                "product_id" => 2,
                "enterprise_id" => 2,
                "product_enterprise_id" => 7790250015536
            ),
            array(
                "product_id" => 3,
                "enterprise_id" => 2,
                "product_enterprise_id" => 7798049449425
            ),
            array(
                "product_id" => 4,
                "enterprise_id" => 2,
                "product_enterprise_id" => 7790250015857
            ),
            array(
                "product_id" => 5,
                "enterprise_id" => 2,
                "product_enterprise_id" => 7798049449876
            ),
            array(
                "product_id" => 6,
                "enterprise_id" => 3,
                "product_enterprise_id" => 43954
            ),
            array(
                "product_id" => 7,
                "enterprise_id" => 3,
                "product_enterprise_id" => 43953
            ),
            array(
                "product_id" => 8,
                "enterprise_id" => 3,
                "product_enterprise_id" => 34352
            ),
            array(
                "product_id" => 9,
                "enterprise_id" => 3,
                "product_enterprise_id" => 31696
            ),
            array(
                "product_id" => 10,
                "enterprise_id" => 2,
                "product_enterprise_id" => 43948
            ),
            array(
                "product_id" => 11,
                "enterprise_id" => 1,
                "product_enterprise_id" => 147489
            ),
            array(
                "product_id" => 12,
                "enterprise_id" => 1,
                "product_enterprise_id" => 147488
            ),
            array(
                "product_id" => 13,
                "enterprise_id" => 1,
                "product_enterprise_id" => 185951
            ),
            array(
                "product_id" => 14,
                "enterprise_id" => 1,
                "product_enterprise_id" => 147200
            ),
            array(
                "product_id" => 15,
                "enterprise_id" => 1,
                "product_enterprise_id" => 147490
            )
        );

        ProductEnterprise::insert($product_enterprise);
    }
}
