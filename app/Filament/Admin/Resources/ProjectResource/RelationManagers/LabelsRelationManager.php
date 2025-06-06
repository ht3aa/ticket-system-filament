<?php

namespace App\Filament\Admin\Resources\ProjectResource\RelationManagers;

use App\Filament\Admin\Resources\ProjectResource\Actions\Table\GenerateLabels;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LabelsRelationManager extends RelationManager
{
    protected static string $relationship = 'labels';


    public function titleField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('title')
            ->required()
            ->unique()
            ->maxLength(255);
    }

    public function colorField(): Forms\Components\ColorPicker
    {
        return Forms\Components\ColorPicker::make('color')
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
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\ColorColumn::make('color'),
                Tables\Columns\TextColumn::make('description'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                GenerateLabels::make()
                    ->projectRecord($this->getOwnerRecord()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }
}
