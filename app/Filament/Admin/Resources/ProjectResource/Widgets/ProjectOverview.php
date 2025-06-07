<?php

namespace App\Filament\Admin\Resources\ProjectResource\Widgets;

use App\Enums\Icons;
use App\Filament\Admin\Resources\ProjectResource\Pages\ListProjects;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProjectOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListProjects::class;
    }

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Projects',
                number_format($this->getTablePageInstance()->getAllTableRecordsCount())
            )
                ->icon(Icons::PROJECT->value),
        ];
    }
}
