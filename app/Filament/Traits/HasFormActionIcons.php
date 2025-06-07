<?php

namespace App\Filament\Traits;

use App\Enums\Icons;
use Filament\Actions\Action;

trait HasFormActionIcons
{
    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->icon(Icons::EDIT->value);
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->icon(Icons::CREATE->value);
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->icon(Icons::CREATE->value);
    }


    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->icon(Icons::CANCEL->value);
    }
}
