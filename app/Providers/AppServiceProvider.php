<?php

namespace App\Providers;

use App\Enums\Icons;
use Closure;
use Illuminate\Support\ServiceProvider;
use Filament\Forms\Components\Select;
use Filament\Actions\CreateAction;
use Filament\Tables\Actions\CreateAction as CreateTableAction;
use Filament\Tables\Actions\EditAction as EditTableAction;
use Filament\Tables\Actions\ExportAction as ExportTableAction;
use Filament\Tables\Actions\ImportAction as ImportTableAction;
use Filament\Tables\Actions\DeleteAction as DeleteTableAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

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
        $this->limitedOptionsMacro();
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
                ->icon(Icons::CREATE->value);
        });

        CreateTableAction::configureUsing(function (CreateTableAction $action) {
            $action
                ->modalSubmitAction(fn($action) => $action->icon(Icons::CREATE->value))
                ->modalCancelAction(fn($action) => $action->icon(Icons::CANCEL->value))
                ->icon(Icons::CREATE->value);
        });


        DeleteAction::configureUsing(function (DeleteAction $action) {
            $action
                ->icon(Icons::DELETE->value);
        });

        DeleteTableAction::configureUsing(function (DeleteTableAction $action) {
            $action
                ->modalSubmitAction(fn($action) => $action->icon(Icons::DELETE->value))
                ->modalCancelAction(fn($action) => $action->icon(Icons::CANCEL->value));
        });

        EditAction::configureUsing(function (EditAction $action) {
            $action
                ->icon(Icons::EDIT->value);
        });

        EditTableAction::configureUsing(function (EditTableAction $action) {
            $action
                ->modalSubmitAction(fn($action) => $action->icon(Icons::EDIT->value))
                ->modalCancelAction(fn($action) => $action->icon(Icons::CANCEL->value));
        });

        ViewAction::configureUsing(function (ViewAction $action) {
            $action
                ->icon(Icons::VIEW->value);
        });

        ExportAction::configureUsing(function (ExportAction $action) {
            $action
                ->icon(Icons::EXPORT->value);
        });

        ImportAction::configureUsing(function (ImportAction $action) {
            $action
                ->icon(Icons::IMPORT->value);
        });

        ExportTableAction::configureUsing(function (ExportTableAction $action) {
            $action
                ->icon(Icons::EXPORT->value);
        });

        ImportTableAction::configureUsing(function (ImportTableAction $action) {
            $action
                ->icon(Icons::IMPORT->value);
        });

        Table::configureUsing(function (Table $table) {
            $table->paginationPageOptions([10, 20, 50, 100]);
        });
    }


    public function limitedOptionsMacro()
    {
        // usage: $this->limitedOptions(ProjectStatus::class, 'title')
        Select::macro('limitedOptions', function (
            string $modelClass,
            string $titleAttribute,
            $limit = 50,
            ?Closure $modifyQueryUsing = null,
            array $columns = ['*']
        ) {
            if ($modifyQueryUsing) {
                $this->options(function () use ($modelClass, $titleAttribute, $limit, $modifyQueryUsing, $columns) {
                    $query = $modelClass::limit($limit);

                    if ($modifyQueryUsing) {
                        $query = $this->evaluate($modifyQueryUsing, [
                            'query' => $query,
                        ]);
                    }

                    return $query->get($columns)->pluck($titleAttribute, 'id');
                });
            } else {
                $this->options($modelClass::limit($limit)->get($columns)->pluck($titleAttribute, 'id'));
            }

            $this->getSearchResultsUsing(function (?string $search) use ($modelClass, $titleAttribute, $limit, $modifyQueryUsing, $columns) {
                $query = $modelClass::where($titleAttribute, 'like', "%{$search}%")->limit($limit);

                if ($modifyQueryUsing) {
                    $query = $this->evaluate($modifyQueryUsing, [
                        'query' => $query,
                    ]);
                }

                return $query->get($columns)->pluck($titleAttribute, 'id');
            });

            return $this;
        });
    }
}
