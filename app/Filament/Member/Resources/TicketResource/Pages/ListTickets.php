<?php

namespace App\Filament\Member\Resources\TicketResource\Pages;

use App\Filament\Member\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $projects = auth()->user()->projectMembers->map(function ($member) {
            return $member->project;
        });

        return $projects->map(function ($project) {
            return Tab::make($project->title)
                ->badge(fn() => once(fn() => $this->getFilteredTableQuery()->where('project_id', $project->id)->count()))
                ->modifyQueryUsing(function ($query) use ($project) {
                    return $query->where('project_id', $project->id);
                });
        })->toArray();
    }
}
