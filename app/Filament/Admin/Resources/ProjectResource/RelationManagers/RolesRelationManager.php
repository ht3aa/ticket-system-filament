<?php

namespace App\Filament\Admin\Resources\ProjectResource\RelationManagers;

use App\Filament\Admin\Resources\ProjectResource\Actions\Table\GenerateRolesAction;
use App\Enums\Icons;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class RolesRelationManager extends RelationManager
{
    protected static string $relationship = 'roles';

    public function titleField(): TextInput
    {
        return TextInput::make('title')
            ->required()
            ->unique(ignoreRecord: true, modifyRuleUsing: fn($rule) => $rule->where('project_id', $this->getOwnerRecord()->id))
            ->maxLength(255);
    }

    public function descriptionField(): TextInput
    {
        return TextInput::make('description')
            ->required()
            ->maxLength(255);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->titleField(),
                $this->descriptionField(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('description'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                GenerateRolesAction::make()
                    ->projectRecord($this->getOwnerRecord()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getIcon(Model $ownerRecord, string $pageClass): ?string
    {
        return Icons::ROLE->value;
    }
}
