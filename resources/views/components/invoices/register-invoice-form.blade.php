<?php

use Livewire\Component;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Schema;

use App\Services\Invoice\InvoiceOcrService;
use App\Services\Product\ProductService;
use App\Services\Enterprise\EnterpriseService;
use App\Services\InvoicePattern\InvoicePatternService;
use App\Services\Invoice\InvoiceService;

use App\Models\Invoice;
use App\Models\Enterprise;

use Filament\Forms\Components;
use Filament\Schemas\Components as SComponents;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;

use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;

use Filament\Notifications\Notification;

use App\Enums\InvoicePatern\PatternInvoiceTypeEnum;

use Illuminate\Database\Eloquent\Collection;

use App\Http\Requests\Enterprise\AttachProductsRequest;

use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;

new class extends Component implements HasSchemas, HasActions {
    use InteractsWithSchemas;
    use InteractsWithActions;

    private InvoiceOcrService $invoiceOcrService;
    private ProductService $productService;
    private EnterpriseService $enterpriseService;
    private InvoicePatternService $invoicePatternService;
    private InvoiceService $invoiceService;

    public ?Invoice $invoice = null;
    public array $data = [];

    protected array $price_options = [];
    protected ?string $product_pattern = null;
    protected ?string $price_pattern = null;

    protected $listeners = [
        'qr-info-captured' => 'QRInfoCaptured',
    ];

    public function boot(InvoiceOcrService $invoiceOcrService, ProductService $productService, EnterpriseService $enterpriseService, InvoicePatternService $invoicePatternService, InvoiceService $invoiceService)
    {
        $this->invoiceOcrService = $invoiceOcrService;
        $this->productService = $productService;
        $this->enterpriseService = $enterpriseService;
        $this->invoicePatternService = $invoicePatternService;
        $this->invoiceService = $invoiceService;
    }

    public function mount(): void
    {
        if (is_null($this->invoice)) {
            $this->form->fill();
            return;
        }

        $data = [
            'invoice_number' => $this->invoice->invoice_number,
            'enterprise_id' => $this->invoice->enterprise_id,
            'paid_at' => $this->invoice->paid_at,
            'products' => $this->invoice
                ->products()
                ->get()
                ->map(
                    fn($product) => [
                        'product_id' => $product->id,
                        'unit_price' => $product->pivot->unit_price,
                        'qty_per_bundle' => $product->qty_per_bundle,
                        'bundles_quantity' => intdiv($product->pivot->quantity, $product->qty_per_bundle),
                        'unit_quantity' => $product->pivot->quantity % $product->qty_per_bundle,
                        'quantity' => $product->pivot->quantity,
                    ],
                )
                ->toArray(),
        ];

        $this->form->fill($data);
    }

    protected function processedQRInfo($data): array
    {
        return [
            'invoice_number' => intval($data['point_of_sale'] . $data['receipt_number']),
            'enterprise_id' => $this->enterpriseService->getEnterpriseByTaxId($data['company_tax_id'])?->id,
            'paid_at' => $data['date'],
        ];
    }

    public function QRInfoCaptured($data): void
    {
        $this->form->fill(self::processedQRInfo($data['qr_data']));
    }

    public function save()
    {
        if (is_null($this->invoice)) {
            $record = $this->invoiceService->createInvoice($this->form->getState());
            $operation = 'created';
        } else {
            $record = $this->invoiceService->updateInvoice($this->invoice->id, $this->form->getState());
            $operation = 'updated';
        }

        if ($record instanceof Invoice) {
            Notification::make()
                ->title('Invoice #' . $record->id . ' ' . $operation . ' successfully')
                ->success()
                ->send();

            $this->form->fill();
        } else {
            Notification::make()->title('Error saving invoice')->danger()->send();
        }
    }

    protected function getEnterprises(string $search): array
    {
        return $this->enterpriseService
            ->filterEnterprises(
                filters: [
                    'enterprise_name' => $search,
                    'enterprise_name_mode' => 'like',
                ],
            )
            ->pluck('name', 'id')
            ->toArray();
    }

    protected function getProducts($search): array
    {
        return $this->productService
            ->filterProducts(
                filters: [
                    'product_name' => $search,
                    'product_name_mode' => 'like',
                ],
            )
            ->pluck('name', 'id')
            ->toArray();
    }

    protected function getInvoicePatterns(int $enterprise_id): array
    {
        if (is_null($enterprise_id)) {
            return [];
        }

        $patterns = [];
        $this->invoicePatternService
            ->filterInvoicePatterns(
                filters: [
                    'enterprise_id' => $enterprise_id,
                    'enterprise_id_mode' => 'eq',
                ],
            )
            ->each(function ($pattern) use (&$patterns) {
                $patterns[$pattern->pattern] = $pattern->type->getLabel();
            });

        return $patterns;
    }

    protected function getPatterns(int $enterprise_id): Collection
    {
        return $this->invoicePatternService->filterInvoicePatterns(
            filters: [
                'enterprise_id' => $enterprise_id,
                'enterprise_id_mode' => 'eq',
            ],
        );
    }

    protected function setPatterns(): void
    {
        $enterprise_id = $this->data['enterprise_id'];
        $patterns = $this->getPatterns($enterprise_id);

        $this->price_pattern = $patterns->where('type', PatternInvoiceTypeEnum::PriceLine->value)->first()?->pattern;
        $this->product_pattern = $patterns->where('type', PatternInvoiceTypeEnum::ProductLine->value)->first()?->pattern;
    }

    protected function processListProduct(): void
    {
        $products = [];
        $pattern = $this->product_pattern;
        foreach ($this->data['photos_product_list'] as $key => $value) {
            $products = array_merge($this->invoiceOcrService->extractProductIdsFromInvoiceImage(path: $value->path(), pattern: $pattern, ids_are_numeric: !is_null($pattern)), $products);
        }

        if (empty($products)) {
            return;
        }

        $filteredProducts = $this->productService
            ->filterProducts(
                filters: [
                    'enterprise_id' => $this->data['enterprise_id'],
                    'enterprise_id_mode' => 'eq',
                    'product_enterprise_ids' => array_values($products),
                    'product_enterprise_ids_mode' => 'in',
                ],
            )
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'qty_per_bundle' => $product->qty_per_bundle,
                ];
            })
            ->toArray();

        $this->form->fill([
            'invoice_number' => $this->data['invoice_number'],
            'enterprise_id' => $this->data['enterprise_id'],
            'paid_at' => $this->data['paid_at'],
            'products' => array_map(
                fn($product) => [
                    'product_id' => $product['id'],
                    'qty_per_bundle' => $product['qty_per_bundle'],
                    'bundles_quantity' => 0,
                    'unit_quantity' => 0,
                    'quantity' => 0,
                ],
                $filteredProducts,
            ),
        ]);
    }

    protected function processListPrices(): void
    {
        $prices = [];
        foreach ($this->data['photos_prices_list'] as $key => $value) {
            $prices = array_merge($this->invoiceOcrService->extractPricesFromInvoiceImage(path: $value->path(), pattern: $this->price_pattern), $prices);
        }

        if (empty($prices)) {
            return;
        }

        $this->price_options = array_unique($prices);
    }

    protected function getRulesFromField(string $field, ?array $params = null): array
    {
        return is_null($this->invoice) ? CreateInvoiceRequest::getRulesFromField($field, $params) : UpdateInvoiceRequest::getRulesFromField($field, $params);
    }

    public function form(Schema $form): Schema
    {
        return $form->statePath('data')->schema([
            SComponents\Wizard::make([
                SComponents\Wizard\Step::make('Information from physical invoice')
                    ->columns(2)
                    ->schema([
                        Components\Select::make('enterprise_id')

                            ->label('Enterprise')
                            ->reactive()
                            ->searchable()
                            ->getSearchResultsUsing(fn(string $search): array => $this->getEnterprises($search))
                            ->getOptionLabelUsing(fn($value): ?string => $this->enterpriseService->getEnterpriseById($value)?->name)
                            ->live()
                            ->rules($this->getRulesFromField('enterprise_id'))
                            ->columnSpanFull()
                            ->required(),

                        Components\TextInput::make('invoice_number')

                            ->rules($this->getRulesFromField('invoice_number', ['enterprise_id' => $this->data['enterprise_id'] ?? null]))
                            ->label('Invoice Number'),

                        Components\DatePicker::make('paid_at')

                            ->rules($this->getRulesFromField('paid_at'))
                            ->label('Payment Date')
                            ->required(),

                        Components\FileUpload::make('photos_product_list')

                            ->dehydrated(false)
                            ->label('List Products')
                            ->columnSpanFull()
                            ->disk('local')
                            ->multiple()
                            ->maxFiles(5)
                            ->image()
                            ->imageEditor()
                            ->helperText('Select only the part you wish to scan. If you need to edit the image, use the editor. Upload a images (Max 5MB). Accepted formats: .jpg, .jpeg, .png, .webp'),

                        Components\FileUpload::make('photos_prices_list')

                            ->label('List Prices')
                            ->columnSpanFull()
                            ->disk('local')
                            ->multiple()
                            ->maxFiles(5)
                            ->image()
                            ->imageEditor()
                            ->dehydrated(false)
                            ->helperText('Select only the part you wish to scan. If you need to edit the image, use the editor. Upload a images (Max 5MB). Accepted formats: .jpg, .jpeg, .png, .webp'),
                    ])
                    ->afterValidation(function () {
                        $this->setPatterns();

                        if (!empty($this->data['photos_prices_list'])) {
                            $this->processListPrices();
                        }

                        if (!empty($this->data['photos_product_list'])) {
                            $this->processListProduct();
                        }
                    }),
                SComponents\Wizard\Step::make('Register Invoice')->schema([
                    Components\Repeater::make('products')
                        ->columns(4)
                        ->schema([
                            Components\Select::make('product_id')
                                ->label('Product')
                                ->rules($this->getRulesFromField('products.*.product_id'))
                                ->searchable()
                                ->distinct()
                                ->getSearchResultsUsing(fn(string $search): array => $this->getProducts($search))
                                ->getOptionLabelUsing(fn($value): ?string => $this->productService->getProductById($value)?->name)
                                ->live()
                                ->afterStateUpdated(function (string $state, Set $set) {
                                    $qty_per_bundle = 1;
                                    if (!empty($state)) {
                                        $qty_per_bundle = $this->productService->getProductById($state)?->qty_per_bundle;
                                    }

                                    $set('qty_per_bundle', $qty_per_bundle);
                                    $set('bundles_quantity', 0);
                                    $set('unit_quantity', 0);
                                    $set('quantity', 0);
                                })
                                ->columnSpan(3)
                                ->required(),

                            Components\TextInput::make('unit_price')

                                ->datalist(fn() => $this->price_options)
                                ->rules($this->getRulesFromField('products.*.unit_price'))
                                ->prefix('$')
                                ->inputMode('decimal')
                                ->numeric()
                                ->columnSpan(1)
                                ->required(),

                            Components\TextInput::make('qty_per_bundle')

                                ->numeric()
                                ->default(1)
                                ->readOnly()
                                ->dehydrated(false),

                            Components\TextInput::make('bundles_quantity')

                                ->numeric()
                                ->default(0)
                                ->dehydrated(false)
                                ->live()
                                ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                                    if (is_numeric($state)) {
                                        $set('quantity', $state * $get('qty_per_bundle') + $get('unit_quantity'));
                                    }
                                }),
                            Components\TextInput::make('unit_quantity')

                                ->numeric()
                                ->dehydrated(false)
                                ->live()
                                ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                                    if (is_numeric($state)) {
                                        $set('quantity', $state + $get('qty_per_bundle') * $get('bundles_quantity'));
                                    }
                                })
                                ->default(0),

                            Components\TextInput::make('quantity')

                                ->label('Total Quantity')
                                ->rules($this->getRulesFromField('products.*.quantity'))
                                ->readOnly()
                                ->numeric()
                                ->default(0)
                                ->required(),
                        ]),
                    Components\Textarea::make('observations')->rules($this->getRulesFromField('observations')),
                ]),
            ])->submitAction(
                new HtmlString(
                    Blade::render(
                        '
                        <x-filament::button
                            type="submit"
                            size="sm"
                            color="success"
                            wire:click="save"
                            wire:loading.aKttr="disabled"
                        >
                            {{ is_null($invoice) ? "Register" : "Update" }}
                        </x-filament::button>
                        ',
                        ['invoice' => $this->invoice],
                    ),
                ),
            ),
        ]);
    }
};
?>

<div>
    <x-filament::section>
        <x-slot name="heading">
            <livewire:invoices.qr-arca-data-capture-modal />
        </x-slot>
        <form wire:submit="save">
            {{ $this->form }}
        </form>

    </x-filament::section>
</div>
