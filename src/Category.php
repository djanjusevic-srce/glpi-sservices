<?php

namespace GlpiPlugin\SServices;

use CommonDropdown;

class Category extends CommonDropdown
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
        return __('Category');
    }

    static function getIcon(): string {
        return "fas fa-heart";
    }

    public static function getTypeName($nb = 0) {
        return __('Category', SSERVICES_SSERVICES);
    }
}
