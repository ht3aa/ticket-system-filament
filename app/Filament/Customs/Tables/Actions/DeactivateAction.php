<?php

namespace App\Filament\Customs\Tables\Actions;

use App\Enums\IconEnum;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class DeactivateAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'deactivate';
    }

    public function updateColumn($column, $value)
    {
        $this->label('تعطيل')
            ->icon(IconEnum::INACTIVE->value)
            ->color('danger')
            ->after(function () {
                Notification::make()
                    ->title('تم تعطيل')
                    ->success()
                    ->send();
            })
            ->requiresConfirmation()
            ->action(fn ($record) => $record->update([$column => $value]));

        return $this;
    }
}
