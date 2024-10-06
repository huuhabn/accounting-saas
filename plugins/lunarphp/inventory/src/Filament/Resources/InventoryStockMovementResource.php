<?php

namespace HS\Inventory\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use HS\Inventory\Models\InventoryStockMovement;
use HS\Inventory\Filament\Resources\InventoryStockMovementResource\Pages;

class InventoryStockMovementResource extends Resource
{
    protected static ?string $model = InventoryStockMovement::class;

    protected static ?string $navigationIcon = 'lucide-replace';

    public static function getNavigationGroup(): ?string
    {
        return __('hs-inventory::lang.section');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('stock_id')
                    ->relationship('stock', 'id')
                    ->required(),
                Forms\Components\TextInput::make('user_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('before')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('after')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('cost')
                    ->numeric()
                    ->default(0.00)
                    ->prefix('$'),
                Forms\Components\TextInput::make('receiver_type')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('receiver_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('reason')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Toggle::make('returned')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stock.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('before')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('after')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('receiver_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('receiver_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reason')
                    ->searchable(),
                Tables\Columns\IconColumn::make('returned')
                    ->boolean(),
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
            'index' => Pages\ListInventoryStockMovements::route('/'),
            'create' => Pages\CreateInventoryStockMovement::route('/create'),
            'edit' => Pages\EditInventoryStockMovement::route('/{record}/edit'),
        ];
    }
}
