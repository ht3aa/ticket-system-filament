<?php

namespace App\Filament\Customs\Tables\Actions;

use App\Filament\Admin\Resources\ActivityLogResource;
use Filament\Tables\Actions\Action;
use Spatie\Activitylog\Models\Activity;

class ActivityAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'activity';
    }

    protected function setUp(): void
    {
        $this->configuration();
    }

    public function configuration()
    {
        return $this
            ->label('Activities')
            ->color('warning')
            ->icon('heroicon-o-clock');

        return $this;
    }

    public function filterByCauserId()
    {
        $this
            ->url(fn($record) => ActivityLogResource::getUrl('index', ['tableFilters[causer_id][value]' => $record->id]));

        return $this;
    }

    public function filterByTableSearch()
    {
        $this
            ->url(fn($record) => ActivityLogResource::getUrl('index', ['tableSearch' => $record->id]));

        return $this;
    }

    public function filterBySubject()
    {
        $this
            ->url(fn($record) => ActivityLogResource::getUrl('index', [
                'tableFilters[subject_type][value]' => $record::class,
                'tableFilters[subject_id][value]' => $record->id,
            ]));

        return $this;
    }
}
