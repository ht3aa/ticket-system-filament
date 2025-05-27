<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Forms\Components\Select;
use Filament\Actions\CreateAction;
use Filament\Tables\Actions\CreateAction as CreateTableAction;
use Filament\Tables\Actions\ExportAction as ExportTableAction;
use Filament\Tables\Actions\ImportAction as ImportTableAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Tables\Table;

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
        $this->globalFilamentConfigurations();
    }



    public function globalFilamentConfigurations()
    {
        Select::configureUsing(function (Select $select) {
            $select
                ->native(false)
                ->preload()
                ->searchable();
        });

        CreateAction::configureUsing(function (CreateAction $action) {
            $action
                ->icon('heroicon-m-plus');
        });

        CreateTableAction::configureUsing(function (CreateTableAction $action) {
            $action
                ->icon('heroicon-m-plus');
        });


        DeleteAction::configureUsing(function (DeleteAction $action) {
            $action
                ->icon('heroicon-m-trash');
        });

        EditAction::configureUsing(function (EditAction $action) {
            $action
                ->icon('heroicon-m-pencil-square');
        });

        ViewAction::configureUsing(function (ViewAction $action) {
            $action
                ->icon('heroicon-m-eye');
        });

        ExportAction::configureUsing(function (ExportAction $action) {
            $action
                ->icon('heroicon-m-arrow-down-tray');
        });

        ImportAction::configureUsing(function (ImportAction $action) {
            $action
                ->icon('heroicon-m-arrow-up-tray');
        });

        ExportTableAction::configureUsing(function (ExportTableAction $action) {
            $action
                ->icon('heroicon-m-arrow-up-tray');
        });

        ImportTableAction::configureUsing(function (ImportTableAction $action) {
            $action
                ->icon('heroicon-m-arrow-down-tray');
        });

        Table::configureUsing(function (Table $table) {
            $table->paginationPageOptions([10, 20, 50, 100]);
        });
    }
}
