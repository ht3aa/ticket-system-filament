<?php

namespace App\Filament\Customs\Tables\Filters;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class CreatedAtFilter extends Filter
{
    public static function getDefaultName(): ?string
    {
        return 'created_at';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->form([
                DatePicker::make('created_from')
                    ->label('تاريخ التسجيل من'),
                DatePicker::make('created_until')
                    ->label('تاريخ التسجيل إلى'),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query
                    ->when(
                        $data['created_from'],
                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                    )
                    ->when(
                        $data['created_until'],
                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                    );
            });
    }
}
