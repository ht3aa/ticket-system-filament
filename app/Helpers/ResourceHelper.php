<?php

namespace App\Helpers;

use Filament\Facades\Filament;
use Filament\FilamentManager;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

class ResourceHelper
{
    public static function getFilamentResourceFromModelName(string $modelName)
    {
        $resources = Filament::getResources();

        foreach ($resources as $resource) {
            if ($resource::getModel() === $modelName) {
                return $resource;
            }
        }

        return null;
    }
}
