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
use App\Services\InvoicePattern\InvoicePatternService;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;
use App\Enums\InvoicePatern\PatternInvoiceTypeEnum;
use Filament\Notifications\Notification;

use App\Http\Requests\Enterprise\AttachProductsRequest;

new class extends Component implements HasSchemas, HasActions {
    use InteractsWithSchemas;
    use InteractsWithActions;

    public ?array $data = [];
    public Enterprise $enterprise;
    public ?string $product_pattern = null;

    private InvoiceOcrService $invoiceOcrService;
    private ProductService $productService;
    private EnterpriseService $enterpriseService;
    private InvoicePatternService $invoicePatternService;

    public function canView(): bool
    {
        return true;
    }

    public function boot(InvoiceOcrService $invoiceOcrService, ProductService $productService, EnterpriseService $enterpriseService, InvoicePatternService $invoicePatternService)
    {
        $this->invoiceOcrService = $invoiceOcrService;
        $this->productService = $productService;
        $this->enterpriseService = $enterpriseService;
        $this->invoicePatternService = $invoicePatternService;
    }

    public function mount(): void
    {
        $this->product_pattern = $this->invoicePatternService
            ->filterInvoicePatterns(
                filters: [
                    'enterprise_id' => $this->enterprise->id,
                    'enterprise_id_mode' => 'eq',
                ],
            )
            ->where('type', PatternInvoiceTypeEnum::ProductLine->value)
            ->first()?->pattern;
        $this->form->fill();
    }

    public function extractProductIdsFromInvoiceImage(string $path): array
    {
        return $this->invoiceOcrService->extractProductIdsFromInvoiceImage(path: $path, pattern: $this->product_pattern, ids_are_numeric: true);
    }

    public function save(): void
    {
        $this->enterpriseService->attachProducts(id: $this->enterprise->id, data: $this->form->getState());
        Notification::make()->title('Saved successfully')->success()->send();
        $this->form->fill();
    }

    public function form(Schema $form): Schema
    {
        return $form->statePath('data')->schema([
            Wizard::make([
                Wizard\Step::make('Add Photos')
                    ->schema([
                        // ...
                        Components\FileUpload::make('photos_ids')
                            ->label('Invoice Photos')
                            ->disk('local')
                            ->multiple()
                            ->maxFiles(5)
                            ->image()
                            ->imageEditor() //
                            ->helperText('Select only the part you wish to scan. If you need to edit the image, use the editor. Upload a images (Max 5MB). Accepted formats: .jpg, .jpeg, .png, .webp'),
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
                            TextInput::make('product_enterprise_id')->required()->numeric()->rules(AttachProductsRequest::getRulesFromField(field: 'products.*.product_enterprise_id', params: ['enterprise_id' => $this->enterprise->id])),
                            Select::make('product_id')
                                ->label('Product')
                                ->required()
                                ->distinct()
                                ->searchable()
                                ->getSearchResultsUsing(
                                    fn(string $search): array => $this->productService
                                        ->filterProducts(
                                            filters: [
                                                'enterprise_id' => $this->enterprise->id,
                                                'enterprise_id_mode' => 'ne',
                                                'product_name' => $search,
                                                'product_name_mode' => 'like',
                                            ],
                                        )
                                        ->pluck('name', 'id')
                                        ->toArray(),
                                )
                                ->getOptionLabelUsing(
                                    fn($value): ?string => $this->productService
                                        ->filterProducts(
                                            filters: [
                                                'product_id' => $value,
                                                'product_id_mode' => 'eq',
                                            ],
                                        )
                                        ->first()->name,
                                ),
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
