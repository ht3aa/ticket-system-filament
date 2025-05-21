<?php

namespace App\Providers;

use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Select::configureUsing(function (Select $select) {
            $select
                ->native(false)
                ->preload()
                ->searchable();
        });

        Table::configureUsing(function (Table $table) {
            $table->paginationPageOptions([10, 20, 50, 100]);
        });
    }
}
