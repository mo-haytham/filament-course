<?php

namespace App\Filament\Resources;

use App\Enums\ProductStatusEnum;
use App\Filament\Forms\Components\MoneyInput;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Products & Orders';

    protected static ?string $recordTitleAttribute = 'name';

    protected static int $globalSearchResultsLimit = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Main Data')
                        ->schema([
                            Forms\Components\Section::make()
                                ->description('User must fill this information')
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', str()->slug($state)))
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('slug')
                                        ->required()
                                        ->readOnly()
                                        ->disabledOn('edit')
                                        ->maxLength(255),
                                    MoneyInput::make('price'),
                                ]),
                        ]),
                    Forms\Components\Wizard\Step::make('Additional Data')
                        ->schema([
                            Forms\Components\Fieldset::make('Important Information')
                                ->schema([
                                    Forms\Components\RichEditor::make('description')
                                        ->columnSpanFull(),
                                    Forms\Components\Radio::make('status')
                                        ->options(ProductStatusEnum::valuesArray()),
                                    Forms\Components\Select::make('category_id')
                                        ->relationship('category', 'name'),
                                    Forms\Components\Select::make('tags')
                                        ->relationship('tags', 'name')
                                        ->multiple(),
                                ]),
                        ]),
                ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->url(fn (Product $record): string => ProductResource::getUrl('view', ['record' => $record->id]))
                    ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('price')
                    ->money('usd')
                    ->getStateUsing(function (Product $product): float {
                        return $product->price / 100;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name'),
                // ->url(fn (Product $product): string => CategoryResource::getUrl('index')),
                // ->url(route('index'), true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (ProductStatusEnum $state): string => match ($state) {
                        ProductStatusEnum::InStock => 'primary',
                        ProductStatusEnum::ComingSoon => 'info',
                        ProductStatusEnum::SoldOut => 'danger',
                    }),
                Tables\Columns\TextColumn::make('tags.name')->badge(),
                Tables\Columns\ToggleColumn::make('is_active'),
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
                Tables\Actions\ViewAction::make()->color('primary'),
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
            'view' => Pages\ViewProduct::route('/{record}'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'description'];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return self::getUrl('view', ['record' => $record]);
    }
}
