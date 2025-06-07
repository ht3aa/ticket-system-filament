<?php

namespace App\Filament\Admin\Resources\ProjectResource\Pages;

use App\Filament\Admin\Resources\ProjectResource;
use App\Filament\Traits\HasFormActionIcons;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    use HasFormActionIcons;

    protected static string $resource = ProjectResource::class;
}
