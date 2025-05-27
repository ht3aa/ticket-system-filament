<?php

namespace App\Filament\Traits;

use Filament\Actions\Action;

trait HasFormActionIcons
{
    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->icon('heroicon-m-pencil-square');
    }


    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->icon('heroicon-m-x-mark');
    }
}
