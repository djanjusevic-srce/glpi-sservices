<?php
// https://glpi-developer-documentation.readthedocs.io/en/latest/plugins/requirements.html#setup-php


const SSERVICES_VERSION = '0.0.28';
const SSERVICES_SSERVICES = 'sservices';

include_once('bootstrap.php');

use GlpiPlugin\SServices\Category;
use GlpiPlugin\SServices\InstallationMethod;
use GlpiPlugin\SServices\LocalUser;
use GlpiPlugin\SServices\SService;
use GlpiPlugin\SServices\Visibility;

/**
 * Init the hooks of the plugins - Needed
 *
 * @return void
 */
function plugin_init_sservices() {

    global $PLUGIN_HOOKS;
    //required!
    $PLUGIN_HOOKS['csrf_compliant'][SSERVICES_SSERVICES] = true;

    Plugin::registerClass(SService::class, [
        'addtabon' => [
            Computer::class,
        ]
    ]);
    Plugin::registerClass(Category::class);
    Plugin::registerClass(LocalUser::class);
    Plugin::registerClass(Visibility::class);
    Plugin::registerClass(InstallationMethod::class);

    $PLUGIN_HOOKS['redefine_menus'][SSERVICES_SSERVICES] = 'sservices_redefine_menus';
    $PLUGIN_HOOKS['use_massive_action'][SSERVICES_SSERVICES] = 1;

    $PLUGIN_HOOKS['menu_toadd'][SSERVICES_SSERVICES] = [
        'assets' => [SService::class,],
    ];

    $PLUGIN_HOOKS['pre_item_purge'][SSERVICES_SSERVICES] = [
        'Computer' => 'sservices_pre_item_purge',
    ];
    $PLUGIN_HOOKS['pre_item_delete'][SSERVICES_SSERVICES] = [
        'Computer' => 'sservices_pre_item_delete',
    ];
    $PLUGIN_HOOKS['pre_item_restore'][SSERVICES_SSERVICES] = [
        'Computer' => 'sservices_pre_item_restore',
    ];
}

/**
 * Get the name and the version of the plugin - Needed
 *
 * @return array
 */
function plugin_version_sservices() {
    return [
        'name'           => 'SServices',
        'version'        => SSERVICES_VERSION,
        'author'         => '<a href="https://gitlab.srce.hr/marko-ivancic">Marko Ivančić</a>',
        'license'        => 'GLPv3',
        'homepage'       => 'https://gitlab.srce.hr/glpi/glpi-plugin-sservices',
        'requirements'   => [
            'glpi'   => [
                'min' => '10.0'
            ]
        ]
    ];
}

/**
 * Optional : check prerequisites before install : may print errors or add to message after redirect
 *
 * @return bool
 */
function plugin_sservices_check_prerequisites(): bool
{
    //do what the checks you want
    return true;
}

/**
 * Check configuration process for plugin : need to return true if succeeded
 * Can display a message only if failure and $verbose is true
 *
 * @param bool $verbose Enable verbosity. Default to false
 *
 * @return bool
 */
function plugin_sservices_check_config(bool $verbose = false): bool
{
    if (true) { // Your configuration check
        return true;
    }

    if ($verbose) {
        echo __('Installed / not configured', SSERVICES_SSERVICES);
    }

    return false;
}

/**
 * Optional: defines plugin options.
 *
 * @return array
 */
function plugin_sservices_options(): array
{
    return [
        Plugin::OPTION_AUTOINSTALL_DISABLED => true,
    ];
}
