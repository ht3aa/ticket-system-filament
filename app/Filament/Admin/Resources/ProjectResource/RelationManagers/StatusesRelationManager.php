<?php

namespace App\Filament\Admin\Resources\ProjectResource\RelationManagers;

use App\Filament\Admin\Resources\ProjectResource\Actions\Table\GenerateStatusesAction;
use App\Filament\Admin\Resources\ProjectResource\Actions\Table\HasChildrenAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatusesRelationManager extends RelationManager
{
    protected static string $relationship = 'statuses';

    public function titleField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('title')
            ->required()
            ->unique(ignoreRecord: true, modifyRuleUsing: fn($rule) => $rule->where('project_id', $this->getOwnerRecord()->id))
            ->columnSpanFull()
            ->maxLength(255);
    }

    public function descriptionField(): Forms\Components\Textarea
    {
        return Forms\Components\Textarea::make('description')
            ->columnSpanFull();
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
            ->modifyQueryUsing(fn($query) => $query->withExists('tickets'))
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('description'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalWidth('md'),
                GenerateStatusesAction::make()
                    ->projectRecord($this->getOwnerRecord()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                HasChildrenAction::make()
                    ->tooltip(function ($record) {
                        $ticketsCount = $record->tickets_exists;

                        return __('system.has_children') . " ({$ticketsCount} tickets)";
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
