<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;

class MoneyInput extends TextInput
{
    public function setup(): void
    {
        $this
            ->mask(RawJs::make('$money($input)'))
            ->formatStateUsing(fn ($state) => is_null($state) ? null : (string) ($state / 100))
            ->dehydrateStateUsing(fn ($state) => is_string($state) ? (int) str_replace([',', '.'], '', number_format((float) $state, 2)) : null)
            ->numeric();
    }
}
