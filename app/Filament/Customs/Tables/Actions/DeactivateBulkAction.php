<?php

namespace App\Filament\Customs\Tables\Actions;

use App\Enums\IconEnum;
use Filament\Tables\Actions\BulkAction;

class DeactivateBulkAction extends BulkAction
{
    public static function getDefaultName(): ?string
    {
        return 'deactivate';
    }

    public function updateColumn($column, $value)
    {
        $this->label('تعطيل')
            ->icon(IconEnum::INACTIVE->value)
            ->action(fn (\Illuminate\Support\Collection $records) => static::getModel()::whereIn('id', $records->pluck('id'))->update([$column => $value]))
            ->color('danger')
            ->requiresConfirmation()
            ->deselectRecordsAfterCompletion();

        return $this;
    }
}
