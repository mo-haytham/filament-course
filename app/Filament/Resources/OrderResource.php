<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\MoneyInput;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Products & Orders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name'),
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name'),
                MoneyInput::make('price'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('product.name'),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->getStateUsing(fn (Order $record) => $record->price / 100)
                    ->sortable()
                    ->summarize(
                        Tables\Columns\Summarizers\Sum::make()
                            ->formatStateUsing(fn ($state) => '$' . number_format($state / 100))
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultGroup('product.name')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),

                Tables\Actions\ActionGroup::make([
                    // Tables\Actions\Action::make('Mark Completed')
                    //     ->requiresConfirmation()
                    //     ->icon('heroicon-o-check-badge')
                    //     ->hidden(fn (Order $record) => $record->is_completed)
                    //     ->action(fn (Order $record) => $record->update(['is_completed' => true])),

                    // Tables\Actions\Action::make('Undo Completed')
                    //     ->requiresConfirmation()
                    //     ->icon('heroicon-o-check-badge')
                    //     ->hidden(fn (Order $record) => !$record->is_completed)
                    //     ->action(fn (Order $record) => $record->update(['is_completed' => false])),

                    Tables\Actions\Action::make('Change is completed')
                        ->icon('heroicon-o-check-badge')
                        ->fillForm(function (Order $order) {
                            return ['is_completed' => $order->is_completed];
                        })
                        ->form([
                            Forms\Components\Checkbox::make('is_completed'),
                        ])
                        ->action(function (Order $order, array $data): void {
                            $order->update(['is_completed' => $data['is_completed']]);
                        }),
                ]),

            ])
            ->headerActions([
                Tables\Actions\Action::make('Products')
                    ->url(fn (): string => ProductResource::getUrl('index')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Mark as Completed')
                        ->icon('heroicon-o-check-badge')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['is_completed' => true]))
                        ->deselectRecordsAfterCompletion()
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOrders::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Order::where('is_completed', false)->count();
    }
}
