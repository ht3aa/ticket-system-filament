<?php

namespace App\Filament\Admin\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('project_id')
                    ->default($this->getOwnerRecord()->id),

                Forms\Components\Select::make('project_member_id')
                    ->relationship('projectMember', 'name')
                    ->required(),

                Forms\Components\Select::make('project_role_id')
                    ->relationship('projectRole', 'title', modifyQueryUsing: fn($query) => $query->where('project_id', $this->getOwnerRecord()->id))
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->modifyQueryUsing(fn(Builder $query) => $query->with(['projectMember', 'projectRole']))
            ->columns([
                Tables\Columns\TextColumn::make('projectMember.name'),
                Tables\Columns\TextColumn::make('projectRole.title'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
