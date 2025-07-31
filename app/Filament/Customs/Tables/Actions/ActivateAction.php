<?php

namespace App\Filament\Customs\Tables\Actions;

use App\Enums\IconEnum;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class ActivateAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'activate';
    }

    public function updateColumn($column, $value)
    {
        $this->label('تفعيل')
            ->icon(IconEnum::ACTIVE->value)
            ->after(function () {
                Notification::make()
                    ->title('تم تفعيل')
                    ->success()
                    ->send();
            })
            ->color('success')
            ->requiresConfirmation()
            ->action(fn ($record) => $record->update([$column => $value]));

        return $this;
    }
}
