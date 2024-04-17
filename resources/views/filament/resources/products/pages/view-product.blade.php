@php
    use App\Enums\ProductStatusEnum;
@endphp
<x-filament-panels::page>
    <h2 class="text-2xl">{{ $record->name }}</h2>

    <div class="mt-4">
        <p class="text-xl font-bold text-yellow-500" {{-- style="color: green;" --}}>{{ $record->price }}</p>
        <p class="text-lg font-bold text-blue" {{-- style="color: blue;" --}}>{{ $record->category->name }}</p>
        <p class="text-lg font-bold text-purple" {{-- style="color: purple;" --}}>{{ $record->tags->pluck('name')->implode(', ') }}</p>
        <p class="text-lg font-bold"
            style="color: {{ match ($record->status) {
                ProductStatusEnum::InStock => 'green',
                ProductStatusEnum::ComingSoon => 'blue',
                ProductStatusEnum::SoldOut => 'red',
            } }}">
            {{ $record->status }}</p>
    </div>

</x-filament-panels::page>
