<?php

namespace Tests\Feature\Invoice;

use App\Models\Invoice;
use App\Models\InvoiceProduct;
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

class UpdateInvoiceTest extends TestCase
{
    // Migra la BD antes de cada test y la limpia al terminar
    use RefreshDatabase;
    use WithFaker;

    protected InvoiceService $invoiceService;
    protected function seedBasicData(): void
    {
        $this->artisan('db:seed', ['--class' => 'ProductSeeder']);
        $this->artisan('db:seed', ['--class' => 'EnterpriseSeeder']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedBasicData();

        $repo = new EloquentInvoiceRepository(new Invoice());
        $userService = new UserService(new EloquentUserRepository(new User()));
        $productService = new ProductService(new EloquentProductRepository(new Product()));

        $this->invoiceService = new InvoiceService($repo, $productService, $userService);
    }
    public function test_update_invoice_happy_path(): void
    {
        $data = [
            'enterprise_id' => 1,
            'products' => [
                [
                    'product_id' => 1,
                    'unit_price' => 100,
                    'quantity' => 1,
                ],
                [
                    'product_id' => 2,
                    'unit_price' => 200,
                    'quantity' => 1,
                ],
            ],
        ];

        $dataUpdate = [
            'products' => [
                [
                    'product_id' => 1,
                    'unit_price' => 100,
                    'quantity' => 10,
                ],
                [
                    'product_id' => 3,
                    'unit_price' => 200,
                    'quantity' => 12,
                ],
            ],
        ];
        $user = User::factory()->create();
        $this->actingAs($user);

        $invoice = $this->invoiceService->createInvoice($data);

        $this->invoiceService->updateInvoice($invoice->id, $dataUpdate);

        $updatedProducts = $invoice->products()
            ->select(['products.id', 'products.stock'])
            ->get()
            ->toArray();

        $detailsInvoice = InvoiceProduct::where('invoice_id', $invoice->id)->get()->toArray();

        $this->assertNotEquals($detailsInvoice, $updatedProducts);

        $this->assertNotNull($invoice);
        $this->assertDatabaseHas('invoice_product', $dataUpdate['products'][1]);
    }
}
