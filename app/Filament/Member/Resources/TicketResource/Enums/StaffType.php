<?php

namespace App\Filament\Member\Resources\TicketResource\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StaffType: string implements HasColor, HasIcon, HasLabel
{
    case ASSIGNEE = 'assignee';
    case ACCOUNTABLE = 'accountable';
    case REPORTER = 'reporter';

    public function getLabel(): string
    {
        return match ($this) {
            self::ASSIGNEE => 'Assignee',
            self::ACCOUNTABLE => 'Accountable',
            self::REPORTER => 'Reporter',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ASSIGNEE => 'primary',
            self::ACCOUNTABLE => 'secondary',
            self::REPORTER => 'success',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::ASSIGNEE => 'heroicon-o-user',
            self::ACCOUNTABLE => 'heroicon-o-user-group',
            self::REPORTER => 'heroicon-o-user-circle',
        };
    }
}
