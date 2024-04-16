<?php

namespace App\Filament\Resources;

use App\Enums\ProductStatusEnum;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric(),
                Forms\Components\Radio::make('status')
                    ->options(ProductStatusEnum::valuesArray()),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name'), Forms\Components\Select::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('price')
                    ->money('usd')
                    ->getStateUsing(function (Product $product): float {
                        return $product->price / 100;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                // ->url(fn (Product $product): string => CategoryResource::getUrl('index')),
                ->url(route('index'),true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (ProductStatusEnum $state): string => match ($state) {
                        ProductStatusEnum::InStock => 'primary',
                        ProductStatusEnum::ComingSoon => 'info',
                        ProductStatusEnum::SoldOut => 'danger',
                    }),
                Tables\Columns\TextColumn::make('tags.name')->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(ProductStatusEnum::valuesArray()),
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                Tables\Filters\Filter::make('name')
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->placeholder("Search by name"),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->where('name', 'like', '%' . $data['name'] . '%');
                    }),
            ], layout: Tables\Enums\FiltersLayout::Modal)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
