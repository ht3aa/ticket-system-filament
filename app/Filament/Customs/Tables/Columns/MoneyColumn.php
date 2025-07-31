<?php

namespace App\Filament\Customs\Tables\Columns;

use Filament\Support\RawJs;
use Filament\Tables\Columns\TextColumn;

class MoneyColumn extends TextColumn
{
    public function setup(): void
    {
        $this
            ->label('السعر (د.ع)')
            ->numeric(decimalPlaces: 0)
            ->suffix(' د.ع');
    }
}
