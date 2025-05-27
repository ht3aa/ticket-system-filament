<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProjectResource\Pages;
use App\Filament\Admin\Resources\ProjectResource\RelationManagers;
use App\Filament\Exports\ProjectExporter;
use App\Filament\Imports\ProjectImporter;
use App\Enums\Icons;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\File;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Project Information')
                    ->icon(Icons::PROJECT->value)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->prefixIcon(Icons::TEXT->value)
                            ->unique(ignoreRecord: true)
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ExportAction::make()
                    ->exporter(ProjectExporter::class),
                ImportAction::make()
                    ->importer(ProjectImporter::class)
                    ->fileRules([
                        File::types(['csv', 'xlsx']),
                    ]),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->icon(Icons::TEXT->value)
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->icon(Icons::TEXT->value)
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LabelsRelationManager::class,
            RelationManagers\StatusesRelationManager::class,
            RelationManagers\RolesRelationManager::class,
            RelationManagers\MembersRelationManager::class,
            RelationManagers\TicketsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }


    public static function getNavigationBadge(): ?string
    {
        return once(fn() => number_format(parent::getEloquentQuery()->count()));
    }

    public static function getNavigationIcon(): string | Htmlable | null
    {
        return Icons::PROJECT->value;
    }
}
