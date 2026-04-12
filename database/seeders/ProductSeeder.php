<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = array(
            array(
                "id" => 1,
                "bar_code" => "89323929382",
                "name" => "HIGIENOL MAX HS 4X100 (NVO)***",
                "stock" => 0,
                "min_stock" => null,
                "created_at" => "2026-04-07 21:57:12",
                "updated_at" => "2026-04-12 13:43:20",
                "deleted_at" => null
            ),
            array(
                "id" => 2,
                "bar_code" => "39843948",
                "name" => "HIGIENOL DH PLUS 4X30M FUSION",
                "stock" => 0,
                "min_stock" => null,
                "created_at" => "2026-04-08 20:27:22",
                "updated_at" => "2026-04-08 20:27:22",
                "deleted_at" => null
            ),
            array(
                "id" => 3,
                "bar_code" => "983239283928",
                "name" => "DUPLEX BLANCO MAX HS X 4X80",
                "stock" => 0,
                "min_stock" => null,
                "created_at" => "2026-04-08 20:28:45",
                "updated_at" => "2026-04-08 20:28:45",
                "deleted_at" => null
            ),
            array(
                "id" => 4,
                "bar_code" => "3892839238928",
                "name" => "HIGIENOL MAX HS 4X80 (NVO)",
                "stock" => 0,
                "min_stock" => null,
                "created_at" => "2026-04-08 20:30:41",
                "updated_at" => "2026-04-08 20:34:28",
                "deleted_at" => null
            ),
            array(
                "id" => 5,
                "bar_code" => "38923928",
                "name" => "DUPLEX DOBLE HOJA X 4 X 30",
                "stock" => 0,
                "min_stock" => null,
                "created_at" => "2026-04-08 20:35:05",
                "updated_at" => "2026-04-08 20:35:05",
                "deleted_at" => null
            )
        );

        Product::insert($products);
    }
}
