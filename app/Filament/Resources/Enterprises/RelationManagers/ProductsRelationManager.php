<?php

namespace App\Filament\Resources\Enterprises\RelationManagers;

use App\Filament\Resources\Enterprises\EnterpriseResource;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Models\Enterprise;
use App\Models\Product;
use App\Models\ProductEnterprise;
use Filament\Actions\Action;
use Filament\Actions\AssociateAction;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('enterprise_id')
                    ->default(function (RelationManager $livewire): ?string {
                        return $livewire->getOwnerRecord()->id;
                    })
                    ->readOnly(),
                Select::make('product_id')
                    ->options(Product::query()->pluck('name', 'id')),
                TextInput::make('product_enterprise_id')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('bar_code')
                    ->searchable(),
                TextColumn::make('pivot.product_enterprise_id')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // CreateAction::make()
                //     ->using(function (array $data, string $model): Model {
                //         Enterprise::findOrFail($data['enterprise_id'])
                //             ->products()
                //             ->attach(
                //                 ids: $data['product_id'],
                //                 attributes: ['product_enterprise_id' => $data['product_enterprise_id']]
                //             );
                //         return Product::find($data['product_id']);
                //     }),
                Action::make('register')
                    ->label("Attach from Invoice")
                    ->url(
                        function (RelationManager $livewire): ?string {
                            return EnterpriseResource::getUrl('attach/products', ['record' => $livewire->getOwnerRecord()->id]);
                        }
                    ),
                AttachAction::make()
                    ->schema(fn(AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('product_enterprise_id')
                            ->numeric(),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
