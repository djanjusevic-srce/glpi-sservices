<?php

namespace GlpiPlugin\SServices;

use CommonDropdown;

class LocalUser extends CommonDropdown
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

    static function getMenuName() {
        return __('Local User');
    }

    static function getIcon() {
        return "fas fa-heart";
    }

    public static function getTypeName($nb = 0) {
        return __('Local User', SSERVICES_SSERVICES);
    }
}
