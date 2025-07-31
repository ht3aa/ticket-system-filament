<?php

namespace App\Filament\Admin\Resources\ActivityLogResource\Pages;

use App\Filament\Customs\InfoList\ActivityLogJson;
use App\Filament\Admin\Resources\ActivityLogResource;
use Filament\Actions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewActivityLog extends ViewRecord
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to List')
                ->icon('heroicon-o-arrow-left')
                ->url(ActivityLogResource::getUrl('index'))
                ->color('gray'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(2)
                    ->schema([
                        Section::make('Activity Details')
                            ->description('Basic information about the recorded activity')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('description')
                                            ->label('Description')
                                            ->placeholder('No Description'),

                                        TextEntry::make('created_at')
                                            ->label('Activity Date')
                                            ->dateTime('d/m/Y H:i:s')
                                            ->icon('heroicon-o-clock'),

                                        TextEntry::make('log_name')
                                            ->label('Log Name')
                                            ->badge()
                                            ->color('primary'),
                                    ]),
                            ])
                            ->columnSpan(6),
                        Section::make('Affected Subject')
                            ->description('Information about the object that was modified')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('subject_type')
                                            ->label('Subject Type')
                                            ->badge()
                                            ->color('info'),

                                        TextEntry::make('subject.id')
                                            ->label('Subject ID')
                                            ->placeholder('Not Defined')
                                            ->numeric(),

                                        TextEntry::make('subject.name')
                                            ->label('Subject Name')
                                            ->placeholder('Not Available')
                                            ->columnSpanFull(),
                                    ]),

                            ])
                            ->columnSpan(6)
                            ->collapsible(),

                    ])
                    ->columns(12),
                Section::make('Responsible User')
                    ->description('Information about the user who performed the operation')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Split::make([

                            Grid::make(2)
                                ->schema([

                                    TextEntry::make('causer.name')
                                        ->label('User Name')
                                        ->placeholder('System')
                                        ->icon('heroicon-o-user'),

                                    TextEntry::make('causer.email')
                                        ->label('Email')
                                        ->placeholder('Not Available')
                                        ->copyable()
                                        ->icon('heroicon-o-envelope'),

                                    TextEntry::make('causer.phone')
                                        ->label('Phone')
                                        ->placeholder('Not Available')
                                        ->copyable()
                                        ->icon('heroicon-o-phone'),

                                    TextEntry::make('causer.user_type')
                                        ->label('User Type')
                                        ->placeholder('Not Available')
                                        ->badge()
                                        ->color('info'),

                                ]),

                            Grid::make(2)
                                ->schema([
                                    ImageEntry::make('causer.avatar_url')
                                        ->label('Profile Image')
                                        ->circular(),

                                    ImageEntry::make('causer.logo')
                                        ->label('Logo')
                                        ->circular(),
                                ]),
                        ]),

                    ])
                    ->collapsible(),

                Section::make('Change Details')
                    ->description('Old and new values for the modified fields')
                    ->icon('heroicon-o-arrow-path')
                    ->schema([
                        ActivityLogJson::make('properties')
                            ->label('Changes')
                            ->placeholder('No old values')
                            ->columnSpanFull()
                            ->keyLabel('Field')
                            ->valueLabel('New Value'),
                    ])
                    ->collapsible()
                    ->visible(
                        fn($record) => ! empty($record->properties['old']) || ! empty($record->properties['attributes'])
                    ),

            ]);
    }
}
