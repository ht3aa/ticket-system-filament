<?php

namespace App\Filament\Admin\Resources\ProjectResource\Actions\Table;

use Filament\Tables\Actions\Action;

class HasChildrenAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'has-children';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Has Children')
            ->color('danger')
            ->icon('heroicon-o-exclamation-triangle')
            ->authorize('hasChildren');
    }
}
