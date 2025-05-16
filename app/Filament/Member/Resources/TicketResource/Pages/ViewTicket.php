<?php

namespace App\Filament\Member\Resources\TicketResource\Pages;

use App\Filament\Member\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Parallax\FilamentComments\Actions\CommentsAction;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            CommentsAction::make(),
        ];
    }
}
