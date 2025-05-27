<?php

namespace App\Filament\Admin\Resources\ProjectResource\RelationManagers;

use App\Filament\Admin\Resources\ProjectResource\Actions\Table\GenerateLabelsAction;
use App\Filament\Admin\Resources\ProjectResource\Actions\Table\HasChildrenAction;
use App\Enums\Icons;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class LabelsRelationManager extends RelationManager
{
    protected static string $relationship = 'labels';

    public function titleField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('title')
            ->required()
            ->prefixIcon(Icons::TEXT->value)
            ->unique(ignoreRecord: true, modifyRuleUsing: fn($rule) => $rule->where('project_id', $this->getOwnerRecord()->id))
            ->maxLength(255);
    }

    public function colorField(): Forms\Components\ColorPicker
    {
        return Forms\Components\ColorPicker::make('color')
            ->prefixIcon(Icons::COLOR->value)
            ->required();
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
                Forms\Components\Grid::make(2)
                    ->schema([
                        $this->titleField(),
                        $this->colorField(),
                    ]),
                $this->descriptionField(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->modifyQueryUsing(fn($query) => $query->withExists('tickets'))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->icon(Icons::TEXT->value),
                Tables\Columns\ColorColumn::make('color'),
                Tables\Columns\TextColumn::make('description')
                    ->icon(Icons::TEXT->value),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                GenerateLabelsAction::make()
                    ->projectRecord($this->getOwnerRecord()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                HasChildrenAction::make()
                    ->tooltip(function ($record) {
                        $ticketsCount = $record->tickets_exists;

                        return __('system.has_children') . " ({$ticketsCount} tickets)";
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getIcon(Model $ownerRecord, string $pageClass): ?string
    {
        return Icons::LABEL->value;
    }
}
