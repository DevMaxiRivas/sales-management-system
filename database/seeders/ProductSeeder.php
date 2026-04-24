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
                "qty_per_bundle" => 10,
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
                "qty_per_bundle" => 10,
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
                "qty_per_bundle" => 10,
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
                "qty_per_bundle" => 10,
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
                "qty_per_bundle" => 10,
                "created_at" => "2026-04-08 20:35:05",
                "updated_at" => "2026-04-08 20:35:05",
                "deleted_at" => null
            ),
            array(
                "id" => 6,
                "bar_code" => "3422334234",
                "name" => "JABON GRANBY BE ROSAS C\\\\\/BICARBONATO 3KG",
                "stock" => 0,
                "min_stock" => null,
                "qty_per_bundle" => 4,
                "created_at" => "2026-04-15 21:42:58",
                "updated_at" => "2026-04-15 21:42:58",
                "deleted_at" => null
            ),
            array(
                "id" => 7,
                "bar_code" => "32423234234",
                "name" => "JABON GRANBY BE LIMON C\\\\\/BICARBONATO 3KG",
                "stock" => 0,
                "min_stock" => null,
                "qty_per_bundle" => 4,
                "created_at" => "2026-04-15 21:43:44",
                "updated_at" => "2026-04-15 21:43:44",
                "deleted_at" => null
            ),
            array(
                "id" => 8,
                "bar_code" => "234234234",
                "name" => "ROLLO DE COCINA CAMPANITA PRACTI 200PAñO",
                "stock" => 0,
                "min_stock" => null,
                "qty_per_bundle" => 20,
                "created_at" => "2026-04-15 21:44:28",
                "updated_at" => "2026-04-15 21:44:28",
                "deleted_at" => null
            ),
            array(
                "id" => 9,
                "bar_code" => "3892839238932",
                "name" => "DETERGENTE MAGISTRAL LIMON 500ML",
                "stock" => 0,
                "min_stock" => null,
                "qty_per_bundle" => 1,
                "created_at" => "2026-04-15 21:44:56",
                "updated_at" => "2026-04-15 21:44:56",
                "deleted_at" => null
            ),
            array(
                "id" => 10,
                "bar_code" => "3892839238911",
                "name" => "JABON GRANBY L.A MANO LIMON C\\\\\/BICARBONATO 3KG",
                "stock" => 0,
                "min_stock" => null,
                "qty_per_bundle" => 1,
                "created_at" => "2026-04-15 21:54:55",
                "updated_at" => "2026-04-15 21:54:55",
                "deleted_at" => null
            ),
            array(
                "id" => 11,
                "bar_code" => "9283923898",
                "name" => "JABON REXONA BAMBOO 3UX120G",
                "stock" => 0,
                "min_stock" => null,
                "qty_per_bundle" => 1,
                "created_at" => "2026-04-15 21:58:19",
                "updated_at" => "2026-04-15 21:59:17",
                "deleted_at" => null
            ),
            array(
                "id" => 12,
                "bar_code" => "2131231231",
                "name" => "JABON REXONA COTTON FRESH 3UX120G",
                "stock" => 0,
                "min_stock" => null,
                "qty_per_bundle" => 1,
                "created_at" => "2026-04-15 21:59:03",
                "updated_at" => "2026-04-15 21:59:03",
                "deleted_at" => null
            ),
            array(
                "id" => 13,
                "bar_code" => "932832983",
                "name" => "JABON REXONA FUTBOL FANATIC 3UX120G",
                "stock" => 0,
                "min_stock" => null,
                "qty_per_bundle" => 1,
                "created_at" => "2026-04-15 21:59:40",
                "updated_at" => "2026-04-15 21:59:40",
                "deleted_at" => null
            ),
            array(
                "id" => 14,
                "bar_code" => "98329238",
                "name" => "JABON REXONA NUTRI ORCHID 3UX120G",
                "stock" => 0,
                "min_stock" => null,
                "qty_per_bundle" => 1,
                "created_at" => "2026-04-15 22:00:13",
                "updated_at" => "2026-04-15 22:00:13",
                "deleted_at" => null
            ),
            array(
                "id" => 15,
                "bar_code" => "3242342342",
                "name" => "JABON REXONA SENSIBLE 3UX120G",
                "stock" => 0,
                "min_stock" => null,
                "qty_per_bundle" => 1,
                "created_at" => "2026-04-15 22:00:45",
                "updated_at" => "2026-04-15 22:00:45",
                "deleted_at" => null
            ),
            array(
                "id" => 16,
                "bar_code" => "9032023032",
                "name" => "LADIYSOFT T.NOR.C\/A X8 SUAV EXT ALG",
                "stock" => 0,
                "min_stock" => null,
                "qty_per_bundle" => 54,
                "created_at" => "2026-04-20 18:53:32",
                "updated_at" => "2026-04-20 18:53:32",
                "deleted_at" => null
            ),
            array(
                "id" => 17,
                "bar_code" => "894384",
                "name" => "ALA MATIC P\/DILUIR X 500ML",
                "stock" => 0,
                "min_stock" => null,
                "qty_per_bundle" => 12,
                "created_at" => "2026-04-20 18:54:03",
                "updated_at" => "2026-04-20 18:54:03",
                "deleted_at" => null
            ),
            array(
                "id" => 18,
                "bar_code" => "90230239",
                "name" => "ELITE PAÑUELOS T.H PACK 6X10",
                "stock" => 0,
                "min_stock" => null,
                "qty_per_bundle" => 35,
                "created_at" => "2026-04-20 18:54:31",
                "updated_at" => "2026-04-20 18:54:31",
                "deleted_at" => null
            ),
            array(
                "id" => 19,
                "bar_code" => "34902893093",
                "name" => "ZORRO MATIC JAB PVO 3KG",
                "stock" => 0,
                "min_stock" => null,
                "qty_per_bundle" => 6,
                "created_at" => "2026-04-20 20:03:08",
                "updated_at" => "2026-04-20 20:03:08",
                "deleted_at" => null
            ),
            array(
                "id" => 20,
                "bar_code" => "324234234",
                "name" => " ZORRO REG CLAS. JAB PVO 3KG",
                "stock" => 0,
                "min_stock" => null,
                "qty_per_bundle" => 6,
                "created_at" => "2026-04-20 20:03:53",
                "updated_at" => "2026-04-20 20:03:53",
                "deleted_at" => null
            ),
            array(
                "id" => 21,
                "bar_code" => "32442342",
                "name" => " ACE LAVA A MANO JAB PVO 800GR",
                "stock" => 0,
                "min_stock" => null,
                "qty_per_bundle" => 20,
                "created_at" => "2026-04-20 20:04:41",
                "updated_at" => "2026-04-20 20:04:41",
                "deleted_at" => null
            )
        );

        Product::insert($products);
    }
}
