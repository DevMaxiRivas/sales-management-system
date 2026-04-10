<?php

use Livewire\Component;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Forms\Components;
use Filament\Schemas\Schema;

use Filament\Schemas\Components\Wizard;

use App\Models\Enterprise;

use App\Services\Invoice\InvoiceOcrService;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

new class extends Component implements HasSchemas {
    use InteractsWithSchemas;

    public ?array $data = [];
    public Enterprise $enterprise;

    private InvoiceOcrService $invoiceOcrService;

    public function canView(): bool
    {
        return true;
    }

    public function boot(InvoiceOcrService $invoiceOcrService)
    {
        $this->invoiceOcrService = $invoiceOcrService;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function extractProductIdsFromInvoiceImage($path): array
    {
        return $this->invoiceOcrService->extractProductIdsFromInvoiceImage($path);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->components([
                Wizard::make([
                    Wizard\Step::make('Add Photos')
                        ->schema([
                            // ...
                            Components\FileUpload::make('photos_ids')->disk('local')->multiple()->minFiles(1)->maxFiles(5)->image()->imageEditor()->storeFiles(false),
                        ])
                        ->afterValidation(function () {
                            $products = [];
                            foreach ($this->data['photos_ids'] as $key => $value) {
                                $products = array_merge($this->extractProductIdsFromInvoiceImage($value->path()), $products);
                            }

                            $this->form->fill([
                                'products' => array_map(fn($id) => ['id' => $id], array_values($products)),
                            ]);
                        }),
                    Wizard\Step::make('Add ID Products')->schema([
                        //
                        Repeater::make('products')
                            //
                            ->schema([
                                //
                                TextInput::make('id')->required(),
                                // Select::make('product_id')
                                // ->options(Product::query()->pluck('name', 'id'))
                                // ->required(),
                            ]),
                    ]),
                ]),
            ])
            ->statePath('data');
    }

    public function update(): void {}
};
?>
<x-filament::section>
    <x-slot name="heading">
        Attach Products to {{ $enterprise->name }}
    </x-slot>
    <form wire:submit="update">
        {{ $this->form }}

        <x-filament::button mt-4 type="submit">Guardar</x-filament::button>
    </form>

</x-filament::section>
