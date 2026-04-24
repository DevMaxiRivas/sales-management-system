<?php

namespace Tests\Feature\Invoice;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Invoice\EloquentInvoiceRepository;
use App\Repositories\Product\EloquentProductRepository;
use App\Repositories\User\EloquentUserRepository;
use App\Services\Invoice\InvoiceService;
use App\Services\Product\ProductService;
use App\Services\User\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetInvoicesTest extends TestCase
{
    // Migra la BD antes de cada test y la limpia al terminar
    use RefreshDatabase;
    use WithFaker;

    protected InvoiceService $invoiceService;
    protected EloquentInvoiceRepository $repo;
    protected function seedBasicData(): void
    {
        $this->artisan('db:seed', ['--class' => 'ProductSeeder']);
        $this->artisan('db:seed', ['--class' => 'EnterpriseSeeder']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedBasicData();

        $userService = new UserService(new EloquentUserRepository(new User()));
        $productService = new ProductService(new EloquentProductRepository(new Product()));

        $this->repo = new EloquentInvoiceRepository(new Invoice());
        $this->invoiceService = new InvoiceService($this->repo, $productService, $userService);
    }
    public function test_create_invoice_happy_path(): void
    {
        $products = [
            [
                'product_id' => 1,
                'unit_price' => 100,
                'quantity' => 1,
            ],
            [
                'product_id' => 2,
                'unit_price' => 200,
                'quantity' => 1,
            ]
        ];

        $data = [
            'enterprise_id' => 1,
            'products' => $products,
        ];

        $user = User::factory()->create();
        $this->actingAs($user);
        $invoice = $this->invoiceService->createInvoice($data);

        $invoiceDetails  = $this->repo->getInvoiceDetails($invoice)->toArray();

        $invoiceDetailsExpeted = [];
        foreach ($products as $product) {
            array_push($invoiceDetailsExpeted, [
                'invoice_id' => $invoice->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'unit_price' => $product['unit_price'],
            ]);
        }

        $this->assertEquals($invoiceDetails, $invoiceDetailsExpeted);
    }
}
