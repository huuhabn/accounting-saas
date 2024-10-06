<?php

namespace HS\Inventory\Filament\Resources;
;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use HS\Inventory\Models\InventoryStockEntry;
use HS\Inventory\Filament\Resources\InventoryStockEntryResource\Pages;

class InventoryStockEntryResource extends Resource
{
    protected static ?string $model = InventoryStockEntry::class;

    protected static ?string $navigationIcon = 'lucide-notebook-tabs';

    public static function getNavigationGroup(): ?string
    {
        return __('hs-inventory::lang.section');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('inventory_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('warehouse_id')
                    ->searchable()
                    ->relationship('warehouse', 'name')
                    ->required(),
                Forms\Components\Select::make('purchase_order_id')
                    ->relationship('purchaseOrder', 'id')
                    ->searchable()
                    ->default(null),
                Forms\Components\TextInput::make('state_before')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('state_after')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('quantity_before')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('quantity_after')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('inventory_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('warehouse.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('purchaseOrder.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state_before')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state_after')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_before')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_after')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventoryStockEntries::route('/'),
            'create' => Pages\CreateInventoryStockEntry::route('/create'),
            'edit' => Pages\EditInventoryStockEntry::route('/{record}/edit'),
        ];
    }
}
