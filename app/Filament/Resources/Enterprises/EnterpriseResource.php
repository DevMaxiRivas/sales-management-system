<?php

namespace App\Filament\Resources\Enterprises;

use App\Filament\Resources\Enterprises\Pages\CreateEnterprise;
use App\Filament\Resources\Enterprises\Pages\EditEnterprise;
use App\Filament\Resources\Enterprises\Pages\ListEnterprises;
use App\Filament\Resources\Enterprises\Pages\Products\AttachProductsFromInvoice;
use App\Filament\Resources\Enterprises\RelationManagers\InvoicePatternsRelationManager;
use App\Filament\Resources\Enterprises\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\Enterprises\Schemas\EnterpriseForm;
use App\Filament\Resources\Enterprises\Tables\EnterprisesTable;
use App\Models\Enterprise;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnterpriseResource extends Resource
{
    protected static ?string $model = Enterprise::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return EnterpriseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EnterprisesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ProductsRelationManager::class,
            InvoicePatternsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEnterprises::route('/'),
            'create' => CreateEnterprise::route('/create'),
            'edit' => EditEnterprise::route('/{record}/edit'),
            'attach/products' => AttachProductsFromInvoice::route('/{record}/attach/products'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
