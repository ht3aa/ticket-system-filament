<?php

namespace App\Filament\Customs\InfoList;

use Filament\Infolists\Components\KeyValueEntry;

class ActivityLogJson extends KeyValueEntry
{
    protected string $view = 'filament.components.infolists.activity-log-json';

    public function getOldValueLabel(): string
    {
        return 'Old Value';
    }
}
