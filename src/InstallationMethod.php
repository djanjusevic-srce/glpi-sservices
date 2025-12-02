<?php

namespace GlpiPlugin\SServices;

use CommonDropdown;

class InstallationMethod extends CommonDropdown
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
        return __('Installation Method');
    }

    static function getIcon(): string {
        return "fas fa-heart";
    }

    public static function getTypeName($nb = 0) {
        return __('Installation Method', SSERVICES_SSERVICES);
    }
}
