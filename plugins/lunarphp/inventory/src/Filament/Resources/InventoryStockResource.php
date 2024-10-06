<?php

namespace HS\Inventory\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use HS\Inventory\Models\InventoryStock;
use HS\Inventory\Filament\Resources\InventoryStockResource\Pages;

class InventoryStockResource extends Resource
{
    protected static ?string $model = InventoryStock::class;

    protected static ?string $navigationIcon = 'lucide-boxes';

    public static function getNavigationGroup(): ?string
    {
        return __('hs-inventory::lang.section');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\Select::make('inventory_id')
                    ->searchable()
                    ->relationship('item', 'sku')
                    ->required(),
                Forms\Components\Select::make('warehouse_id')
                    ->searchable()
                    ->relationship('warehouse', 'name')
                    ->required(),
                Forms\Components\TextInput::make('available_stock')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('committed_stock')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('reason')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->default(0.00)
                    ->prefix('$'),
                Forms\Components\TextInput::make('aisle')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('row')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('bin')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inventory_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('warehouse.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('available_stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('committed_stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reason')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cost')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('aisle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('row')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
            'index' => Pages\ListInventoryStocks::route('/'),
            'create' => Pages\CreateInventoryStock::route('/create'),
            'edit' => Pages\EditInventoryStock::route('/{record}/edit'),
        ];
    }
}
