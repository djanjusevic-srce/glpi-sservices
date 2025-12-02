<?php
// https://glpi-developer-documentation.readthedocs.io/en/latest/plugins/requirements.html#setup-php

global $CFG_GLPI;

include_once('bootstrap.php');

use GlpiPlugin\SServices\Category;
use GlpiPlugin\SServices\InstallationMethod;
use GlpiPlugin\SServices\LocalUser;
use GlpiPlugin\SServices\SService;
use GlpiPlugin\SServices\Visibility;

const SSERVICES_SSERVICES = 'sservices';
const SSERVICES_VERSION = '0.0.28';

define('PLUGIN_SSERVICES_NAME', 'sservices');
define('PLUGIN_SSERVICES_VERSION', '0.0.28');

if (!defined("PLUGIN_SSERVICES_DIR")) {
    define("PLUGIN_SSERVICES_DIR", Plugin::getPhpDir("sservices"));
    //    define("PLUGIN_SSERVICES_WEBDIR", Plugin::getPhpDir("accounts", false));
    $root = $CFG_GLPI['root_doc'] . '/plugins/sservices';
    define("PLUGIN_SSERVICES_WEBDIR", $root);
}


/**
 * Init the hooks of the plugins - Needed
 *
 * @return void
 */
function plugin_init_sservices() {

    global $PLUGIN_HOOKS;
    
    Toolbox::logInFile('sservices', "=== Initializing SServices Plugin ===\n");
    
    //required!
    $PLUGIN_HOOKS['csrf_compliant'][SSERVICES_SSERVICES] = true;
    Toolbox::logInFile('sservices', "CSRF compliance enabled\n");

    Plugin::registerClass(SService::class, [
        'addtabon' => [
            Computer::class,
        ]
    ]);
    Toolbox::logInFile('sservices', "Registered SService class with Computer tab\n");
    
    Plugin::registerClass(Category::class);
    Toolbox::logInFile('sservices', "Registered Category class\n");
    
    Plugin::registerClass(LocalUser::class);
    Toolbox::logInFile('sservices', "Registered LocalUser class\n");
    
    Plugin::registerClass(Visibility::class);
    Toolbox::logInFile('sservices', "Registered Visibility class\n");
    
    Plugin::registerClass(InstallationMethod::class);
    Toolbox::logInFile('sservices', "Registered InstallationMethod class\n");

    $PLUGIN_HOOKS['redefine_menus'][SSERVICES_SSERVICES] = 'sservices_redefine_menus';
    $PLUGIN_HOOKS['use_massive_action'][SSERVICES_SSERVICES] = 1;
    Toolbox::logInFile('sservices', "Menu and massive action hooks registered\n");

    $PLUGIN_HOOKS['menu_toadd'][SSERVICES_SSERVICES] = [
        'assets' => [SService::class,],
    ];
    Toolbox::logInFile('sservices', "Menu entry added to Assets\n");

    $PLUGIN_HOOKS['pre_item_purge'][SSERVICES_SSERVICES] = [
        'Computer' => 'sservices_pre_item_purge',
    ];
    Toolbox::logInFile('sservices', "Registered pre_item_purge hook for Computer\n");
    
    $PLUGIN_HOOKS['pre_item_delete'][SSERVICES_SSERVICES] = [
        'Computer' => 'sservices_pre_item_delete',
    ];
    Toolbox::logInFile('sservices', "Registered pre_item_delete hook for Computer\n");
    
    $PLUGIN_HOOKS['pre_item_restore'][SSERVICES_SSERVICES] = [
        'Computer' => 'sservices_pre_item_restore',
    ];
    Toolbox::logInFile('sservices', "Registered pre_item_restore hook for Computer\n");
    
    Toolbox::logInFile('sservices', "=== SServices Plugin Initialization Completed ===\n\n");
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
        'homepage'       => 'https://gitlab.srce.hr/marko-ivancic',
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
    Toolbox::logInFile('sservices', "Checking plugin prerequisites\n");
    
    //do what the checks you want
    $result = true;
    
    if ($result) {
        Toolbox::logInFile('sservices', "Prerequisites check passed\n");
    } else {
        Toolbox::logInFile('sservices', "Prerequisites check FAILED\n");
    }
    
    return $result;
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
    Toolbox::logInFile('sservices', "Checking plugin configuration\n");
    
    if (true) { // Your configuration check
        Toolbox::logInFile('sservices', "Configuration check passed\n");
        return true;
    }

    if ($verbose) {
        echo __('Installed / not configured', SSERVICES_SSERVICES);
    }

    Toolbox::logInFile('sservices', "Configuration check FAILED\n");
    return false;
}

/**
 * Optional: defines plugin options.
 *
 * @return array
 */
function plugin_sservices_options(): array
{
    Toolbox::logInFile('sservices', "Loading plugin options - autoinstall disabled\n");
    
    return [
        Plugin::OPTION_AUTOINSTALL_DISABLED => true,
    ];
}
