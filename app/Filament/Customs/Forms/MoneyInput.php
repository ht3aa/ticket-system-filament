<?php

namespace App\Filament\Customs\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;

class MoneyInput extends TextInput
{

    public function setup(): void
    {
        $this
            ->numeric()
            ->suffix('د.ع') // "IQD" in Arabic
            ->mask(RawJs::make('$money($input)'))
            ->stripCharacters(',')
            ->label('السعر (عراقي)')
            ->placeholder('أدخل السعر بالعراقي');
    }
}
