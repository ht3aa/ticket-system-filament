<?php

namespace App\Filament\Admin\Resources;

use App\Enums\ActivityLogEvent;
use App\Filament\Admin\Resources\ActivityLogResource\Pages;
use App\Helpers\ResourceHelper;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Activity Logs';

    protected static ?string $modelLabel = 'Activity Log';

    protected static ?string $pluralModelLabel = 'Activity Logs';

    protected static ?int $navigationSort = 99;

    public static function form(Form $form): Form
    {
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->with('subject', 'causer')->latest())
            ->pushColumns([

                Tables\Columns\ImageColumn::make('causer.avatar_url')
                    ->label('Image')
                    ->circular(),

                Tables\Columns\TextColumn::make('causer.name')
                    ->label('User')
                    ->placeholder('System')
                    ->icon('heroicon-o-user')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('causer.email')
                    ->label('Email')
                    ->placeholder('System')
                    ->searchable(),

                Tables\Columns\TextColumn::make('causer.phone')
                    ->label('Phone')
                    ->placeholder('System')
                    ->searchable(),

                Tables\Columns\TextColumn::make('event')
                    ->label('Event Type')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject_id')
                    ->label('Subject ID')
                    ->searchable()
                    ->tooltip('Click to view the subject if found')
                    ->url(function (Activity $record) {
                        $subjectType = $record->subject_type;

                        $filamentResource = ResourceHelper::getFilamentResourceFromModelName($subjectType);

                        if ($filamentResource) {
                            $pages = $filamentResource::getPages();

                            if (isset($pages['view'])) {
                                return $filamentResource::getUrl('view', ['record' => $record->subject_id]);
                            }

                            if (isset($pages['edit'])) {
                                return $filamentResource::getUrl('edit', ['record' => $record->subject_id]);
                            }
                        }

                        return null;
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Subject Type')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject')
                    ->placeholder('Not Available')
                    ->limit(30),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->placeholder('No Description')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\TextColumn::make('log_name')
                    ->label('Log Name')
                    ->badge()
                    ->color('primary')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->pushFilters([
                Tables\Filters\SelectFilter::make('event')
                    ->label('Event Type')
                    ->options(ActivityLogEvent::class)
                    ->multiple(),

                Tables\Filters\SelectFilter::make('subject_id')
                    ->label('Subject ID')
                    ->preload()
                    ->options(fn(Builder $query) => Activity::limit(50)->pluck('subject_id', 'subject_id'))
                    ->getSearchResultsUsing(fn(string $search) => Activity::where('subject_id', 'like', "%{$search}%")->limit(50)->pluck('subject_id', 'subject_id'))
                    ->searchable(),

                Tables\Filters\SelectFilter::make('subject_type')
                    ->options(fn() => collect(glob(app_path('Models') . '/*.php'))->map(fn($file) => 'App\Models\\' . basename($file, '.php'))->mapWithKeys(fn($class) => [$class => $class]))
                    ->label('Subject Type'),

                Tables\Filters\SelectFilter::make('causer_id')
                    ->label('User')
                    ->preload()
                    ->options(function (Builder $query, $livewire) {
                        return User::limit(50)
                            ->when(isset($livewire->tableFilters['causer_id']['value']), function ($query) use ($livewire) {
                                return $query->where('id', $livewire->tableFilters['causer_id']['value']);
                            })
                            ->get()
                            ->pluck('name', 'id');
                    })
                    ->getSearchResultsUsing(fn(string $query) => User::where('name', 'like', "%{$query}%")->limit(50)->pluck('name', 'id'))
                    ->searchable(),

            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('View')
                    ->icon('heroicon-o-eye'),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return once(fn() => self::getEloquentQuery()->count());
    }
}
