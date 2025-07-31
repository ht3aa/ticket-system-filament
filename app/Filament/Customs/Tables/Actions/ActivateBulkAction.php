<?php

namespace App\Filament\Customs\Tables\Actions;

use App\Enums\IconEnum;
use Filament\Tables\Actions\BulkAction;

class ActivateBulkAction extends BulkAction
{
    public static function getDefaultName(): ?string
    {
        return 'activate';
    }

    public function updateColumn($column, $value)
    {
        $this->label('تفعيل')
            ->icon(IconEnum::ACTIVE->value)
            ->action(fn (\Illuminate\Support\Collection $records) => static::getModel()::whereIn('id', $records->pluck('id'))->update([$column => $value]))
            ->color('success')
            ->deselectRecordsAfterCompletion()
            ->requiresConfirmation();

        return $this;
    }
}
