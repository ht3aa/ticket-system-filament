<?php

namespace App\Enums;

enum Icons: string
{
    case CREATE = 'heroicon-m-plus';
    case EDIT = 'heroicon-m-pencil-square';
    case VIEW = 'heroicon-m-eye';
    case DELETE = 'heroicon-m-trash';
    case CANCEL = 'heroicon-m-x-mark';
    case EXPORT = 'heroicon-m-arrow-down-tray';
    case IMPORT = 'heroicon-m-arrow-up-tray';
    case GENERATE = 'heroicon-m-adjustments-vertical';
    case PROJECT = 'heroicon-m-folder';
    case TEXT = 'heroicon-m-document-text';
    case COLOR = 'heroicon-m-swatch';
    case LABEL = 'heroicon-m-tag';
    case STATUS = 'heroicon-m-check-circle';
    case MEMBER = 'heroicon-m-user-group';
    case TICKET = 'heroicon-m-ticket';
    case ROLE = 'heroicon-m-briefcase';
}
