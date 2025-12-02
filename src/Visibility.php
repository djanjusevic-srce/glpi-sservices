<?php

namespace GlpiPlugin\SServices;

use CommonDropdown;

class Visibility extends CommonDropdown
{
    public static function canView(): bool
    {
        return true;
    }

    public static function canCreate(): bool 
    {
        return true;
    }

    public static function canDelete(): bool
    {
        return true;
    }

    static function getMenuName(): string {
        return __('Visibility');
    }

    static function getIcon(): string {
        return "fas fa-heart";
    }

    public static function getTypeName($nb = 0) {
        return __('Visibility', SSERVICES_SSERVICES);
    }
}
