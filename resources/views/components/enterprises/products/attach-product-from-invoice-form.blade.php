<?php

use Livewire\Component;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Forms\Components;
use Filament\Schemas\Schema;

use Filament\Schemas\Components\Wizard;

use App\Models\Enterprise;

use App\Services\Invoice\InvoiceOcrService;
use App\Services\Product\ProductService;
use App\Services\Enterprise\EnterpriseService;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;

use Filament\Notifications\Notification;

use App\Http\Requests\Enterprise\AttachProductsRequest;

new class extends Component implements HasSchemas, HasActions {
    use InteractsWithSchemas;
    use InteractsWithActions;

    public ?array $data = [];
    public Enterprise $enterprise;

    private InvoiceOcrService $invoiceOcrService;
    private ProductService $productService;
    private EnterpriseService $enterpriseService;

    public function canView(): bool
    {
        return true;
    }

    public function boot(InvoiceOcrService $invoiceOcrService, ProductService $productService, EnterpriseService $enterpriseService)
    {
        $this->invoiceOcrService = $invoiceOcrService;
        $this->productService = $productService;
        $this->enterpriseService = $enterpriseService;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function extractProductIdsFromInvoiceImage($path): array
    {
        return $this->invoiceOcrService->extractProductIdsFromInvoiceImage($path);
    }

    public function save(): void
    {
        $this->enterpriseService->attachProducts(id: $this->enterprise->id, data: $this->form->getState());
        Notification::make()->title('Saved successfully')->success()->send();
        $this->form->fill();
    }

    public function form(Schema $form): Schema
    {
        $products = [];

        $this->productService
            ->filterProducts(
                filters: [
                    'enterprise_id' => $this->enterprise->id,
                    'enterprise_id_mode' => 'ne',
                ],
            )
            ->each(function ($product) use (&$products) {
                $products[$product->id] = $product->name;
            });

        return $form->statePath('data')->schema([
            Wizard::make([
                Wizard\Step::make('Add Photos')
                    ->schema([
                        // ...
                        Components\FileUpload::make('photos_ids')->disk('local')->multiple()->maxFiles(5)->image()->imageEditor()->storeFiles(false),
                    ])
                    ->afterValidation(function () {
                        $products = [];
                        foreach ($this->data['photos_ids'] as $key => $value) {
                            $products = array_merge($this->extractProductIdsFromInvoiceImage($value->path()), $products);
                        }

                        $this->form->fill([
                            'products' => array_map(fn($id) => ['product_enterprise_id' => $id], array_values($products)),
                        ]);
                    }),
                Wizard\Step::make('Add ID Products')->schema([
                    //
                    Repeater::make('products')
                        //
                        ->schema([
                            //
                            TextInput::make('product_enterprise_id')
                                ->required()
                                ->numeric() //
                                ->rules(AttachProductsRequest::getRulesFromField(field: 'products.*.product_enterprise_id', params: ['enterprise_id' => $this->enterprise->id])),
                            Select::make('product_id')->options($products)->distinct()->required(),
                        ])
                        ->columns(2)
                        ->minItems(1),
                ]),
            ])->submitAction(
                new HtmlString(
                    Blade::render(
                        <<<BLADE
                        <x-filament::button
                            type="submit"
                            size="sm"
                            color="success"
                            wire:click="save"
                            wire:loading.aKttr="disabled"
                        >
                            Register
                        </x-filament::button>
                        BLADE
                        ,
                    ),
                ),
            ),
        ]);
    }
};
?>
<x-filament::section>
    <x-slot name="heading">
        Attach Products to {{ $enterprise->name }}
    </x-slot>
    <form wire:submit="save">
        {{ $this->form }}
    </form>

</x-filament::section>
