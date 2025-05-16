<?php

namespace App\Filament\Member\Resources;

use App\Filament\Member\Resources\TicketResource\Enums\StaffType;
use App\Filament\Member\Resources\TicketResource\Pages;
use App\Filament\Member\Resources\TicketResource\RelationManagers;
use App\Models\Project;
use App\Models\ProjectLabel;
use App\Models\ProjectStatus;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ticket Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required(),
                        TiptapEditor::make('description')
                            ->profile('simple')
                            ->required(),
                    ])
                    ->columnSpan(8),
                Section::make('Ticket Information')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->default(fn() => 'TICKET-' . str_pad(Ticket::count() + 1, 4, '0', STR_PAD_LEFT))
                            ->readOnly()
                            ->required(),


                        Forms\Components\Select::make('project_id')
                            ->options(Project::all()->pluck('title', 'id'))
                            ->extraInputAttributes([
                                '@change' => "() => {
                                    \$wire.set('data.project_status_id', null);
                                    \$wire.set('data.project_label_id', null);
                                }",
                            ])
                            ->required(),

                        Forms\Components\Select::make('project_status_id')
                            ->options(fn($get) => ProjectStatus::where('project_id', $get('project_id'))->pluck('title', 'id'))
                            ->required(),

                        Forms\Components\Select::make('project_label_id')
                            ->options(fn($get) => ProjectLabel::where('project_id', $get('project_id'))->pluck('title', 'id'))
                            ->required(),

                        Forms\Components\Select::make('parent_id')
                            ->options(Ticket::all()->pluck('title', 'id')),
                    ])
                    ->columnSpan(4),

            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferFilters()
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('parent_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('projectStatus.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('projectLabel.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('staff.type')
                    ->options(StaffType::class)
                    ->modifyQueryUsing(function (Builder $query, $state) {
                        if (! empty($state['value'])) {
                            return $query->whereHas('staff', function (Builder $query) use ($state) {
                                $query->whereIn('project_member_id', auth()->user()->projectMembers->pluck('id'))->where('type', $state);
                            });
                        }

                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\StaffRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'view' => Pages\ViewTicket::route('/{record}'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['projectStatus', 'project', 'projectLabel']);
    }
}
