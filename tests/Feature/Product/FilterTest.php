<?php

namespace Tests\Feature\Product;

use App\Models\Enterprise;
use App\Models\Product;
use App\Repositories\Product\EloquentProductRepository;
use App\Services\Product\ProductService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FilterTest extends TestCase
{
    // Migra la BD antes de cada test y la limpia al terminar
    use RefreshDatabase;
    use WithFaker;

    protected ProductService $productService;

    protected function seedBasicData(): void
    {
        $this->artisan('db:seed', ['--class' => 'ProductSeeder']);
        $this->artisan('db:seed', ['--class' => 'EnterpriseSeeder']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedBasicData();

        $repo = new EloquentProductRepository(new Product());

        $this->productService = new ProductService($repo);
    }
    public function test_products_in_enterprise(): void
    {
        $enterprise_id = 1;
        $products = $this->productService
            ->filterProducts(
                filters: [
                    'enterprise_id' => $enterprise_id,
                    'enterprise_id_mode' => 'eq',
                ],
            )
            ->pluck('name', 'id');

        $this->assertCount(5, $products);
    }
}
