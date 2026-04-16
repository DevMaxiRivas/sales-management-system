<?php

namespace App\Filament\Resources\Enterprises\RelationManagers;

use App\Enums\InvoicePatern\PatternInvoiceTypeEnum;
use App\Http\Requests\Enterprise\AttachPatternInvoiceRequest;
use Filament\Actions\AssociateAction;
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

class InvoicePatternsRelationManager extends RelationManager
{
    protected static string $relationship = 'invoice_patterns';

    protected static ?string $title = 'Invoice patterns';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->options(PatternInvoiceTypeEnum::class)
                    ->rules(
                        function (Get $get) {
                            return AttachPatternInvoiceRequest::getRulesFromField(
                                field: 'type',
                                params: ['enterprise_id' => $this->ownerRecord->id, 'record_id' => $get('id')]
                            );
                        }
                    ),
                TextInput::make('pattern')
                    ->rules(AttachPatternInvoiceRequest::getRulesFromField('pattern', ['enterprise_id' => $this->ownerRecord->id])),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('pattern'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->label("Register")
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
