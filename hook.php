<?php

use GlpiPlugin\SServices\Category;
use GlpiPlugin\SServices\InstallationMethod;
use GlpiPlugin\SServices\LocalUser;
use GlpiPlugin\SServices\Visibility;
use GlpiPlugin\SServices\SService;

include_once('bootstrap.php');

/**
 * Install hook
 *
 * @return boolean
 */
function plugin_sservices_install(): bool
{
    global $DB;

    Toolbox::logInFile('sservices', "=== Starting SServices Plugin Installation ===\n");

    $DB->allow_signed_keys = false;

    $migrationVersion = 100;
    $migration = new Migration($migrationVersion);

    $defaultCharset = DBConnection::getDefaultCharset();
    $defaultCollation = DBConnection::getDefaultCollation();
    $defaultKeySign = DBConnection::getDefaultPrimaryKeySignOption();

    Toolbox::logInFile('sservices', "Database settings - Charset: $defaultCharset, Collation: $defaultCollation\n");

    $tableNameMigrations = 'glpi_plugin_sservices_migrations';
    if (!$DB->tableExists($tableNameMigrations)) {
        Toolbox::logInFile('sservices', "Creating migrations table: $tableNameMigrations\n");
        $query = "CREATE TABLE `$tableNameMigrations` (
                  `version` INT(11) NOT NULL,
                  KEY `version` (`version`)
               ) ENGINE=InnoDB DEFAULT CHARSET=$defaultCharset COLLATE=$defaultCollation";
        $DB->doQuery($query) or die($DB->error());
        Toolbox::logInFile('sservices', "Migrations table created successfully\n");
    } else {
        Toolbox::logInFile('sservices', "Migrations table already exists\n");
    }

    $migratedVersions = [];
    $migrationsIterator = $DB->request(['FROM' => $tableNameMigrations]);
    
    foreach ($migrationsIterator as $row) {
        $migratedVersions[] = $row['version'];
    }

    Toolbox::logInFile('sservices', "Previously migrated versions: " . implode(', ', $migratedVersions) . "\n");

    $tableNameSServices = 'glpi_plugin_sservices_sservices';
    if (!in_array($migrationVersion, $migratedVersions)) {
        Toolbox::logInFile('sservices', "Running migration version $migrationVersion\n");
        
        if (!$DB->tableExists($tableNameSServices)) {
            Toolbox::logInFile('sservices', "Creating main services table: $tableNameSServices\n");
            $query = "CREATE TABLE `$tableNameSServices` (
                      `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                      `name` VARCHAR(255) NOT NULL,
                      PRIMARY KEY  (`id`)
                   ) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=$defaultCharset COLLATE=$defaultCollation";
            $DB->doQuery($query) or die($DB->error());
            Toolbox::logInFile('sservices', "Main services table created successfully\n");
        } else {
            Toolbox::logInFile('sservices', "Main services table already exists\n");
        }

        if ($DB->tableExists($tableNameSServices)) {
            Toolbox::logInFile('sservices', "Adding fields to $tableNameSServices\n");
            
            $migration->addField($tableNameSServices, 'is_deleted', 'bool');
            Toolbox::logInFile('sservices', "- Added field: is_deleted\n");

            $migration->addField($tableNameSServices, 'computers_id', 'fkey');
            $migration->addKey($tableNameSServices, ['computers_id'], 'computers_id');
            Toolbox::logInFile('sservices', "- Added field and key: computers_id\n");

            $migration->addField($tableNameSServices, 'plugin_sservices_categories_id', 'fkey');
            $migration->addKey($tableNameSServices, 'plugin_sservices_categories_id');
            Toolbox::logInFile('sservices', "- Added field and key: plugin_sservices_categories_id\n");

            $migration->addField($tableNameSServices, 'info', 'string');
            Toolbox::logInFile('sservices', "- Added field: info\n");

            $migration->addField($tableNameSServices, 'users_id', 'fkey');
            $migration->addKey($tableNameSServices, 'users_id');
            Toolbox::logInFile('sservices', "- Added field and key: users_id\n");

            $migration->addField($tableNameSServices, 'groups_id', 'fkey');
            $migration->addKey($tableNameSServices, 'groups_id');
            Toolbox::logInFile('sservices', "- Added field and key: groups_id\n");

            $migration->addField($tableNameSServices, 'plugin_sservices_localusers_id', 'fkey');
            $migration->addKey($tableNameSServices, 'plugin_sservices_localusers_id');
            Toolbox::logInFile('sservices', "- Added field and key: plugin_sservices_localusers_id\n");

            $migration->addField($tableNameSServices, 'plugin_sservices_visibilities_id', 'fkey');
            $migration->addKey($tableNameSServices, 'plugin_sservices_visibilities_id');
            Toolbox::logInFile('sservices', "- Added field and key: plugin_sservices_visibilities_id\n");

            $migration->addField($tableNameSServices, 'is_monitored', 'bool', ['value' => 0]);
            $migration->addKey($tableNameSServices, 'is_monitored');
            Toolbox::logInFile('sservices', "- Added field and key: is_monitored\n");

            $migration->addField($tableNameSServices, 'plugin_sservices_installationmethods_id', 'fkey');
            $migration->addKey($tableNameSServices, 'plugin_sservices_installationmethods_id');
            Toolbox::logInFile('sservices', "- Added field and key: plugin_sservices_installationmethods_id\n");

            $migration->addField($tableNameSServices, 'comment', 'string');
            Toolbox::logInFile('sservices', "- Added field: comment\n");
        }

        $tableNameCategories = 'glpi_plugin_sservices_categories';
        if (!$DB->tableExists($tableNameCategories)) {
            Toolbox::logInFile('sservices', "Creating categories table: $tableNameCategories\n");
            $query = "CREATE TABLE `$tableNameCategories` (
                      `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                      `name` VARCHAR(255) NOT NULL,
                      `comment` text,
                      PRIMARY KEY  (`id`),
                      KEY `name` (`name`)
                   ) ENGINE=InnoDB  DEFAULT CHARSET=$defaultCharset COLLATE=$defaultCollation";
            $DB->doQuery($query) or die($DB->error());
            Toolbox::logInFile('sservices', "Categories table created successfully\n");
        } else {
            Toolbox::logInFile('sservices', "Categories table already exists\n");
        }

        $tableNameLocalUsers = 'glpi_plugin_sservices_localusers';
        if (!$DB->tableExists($tableNameLocalUsers)) {
            Toolbox::logInFile('sservices', "Creating local users table: $tableNameLocalUsers\n");
            $query = "CREATE TABLE `$tableNameLocalUsers` (
                      `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                      `name` VARCHAR(255) NOT NULL,
                      `comment` text,
                      PRIMARY KEY  (`id`),
                      KEY `name` (`name`)
                   ) ENGINE=InnoDB  DEFAULT CHARSET=$defaultCharset COLLATE=$defaultCollation";
            $DB->doQuery($query) or die($DB->error());
            Toolbox::logInFile('sservices', "Local users table created successfully\n");
        } else {
            Toolbox::logInFile('sservices', "Local users table already exists\n");
        }

        $tableNameVisibilities = 'glpi_plugin_sservices_visibilities';
        if (!$DB->tableExists($tableNameVisibilities)) {
            Toolbox::logInFile('sservices', "Creating visibilities table: $tableNameVisibilities\n");
            $query = "CREATE TABLE `$tableNameVisibilities` (
                      `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                      `name` VARCHAR(255) NOT NULL,
                      `comment` text,
                      PRIMARY KEY  (`id`),
                      KEY `name` (`name`)
                   ) ENGINE=InnoDB  DEFAULT CHARSET=$defaultCharset COLLATE=$defaultCollation";
            $DB->doQuery($query) or die($DB->error());
            Toolbox::logInFile('sservices', "Visibilities table created successfully\n");
        } else {
            Toolbox::logInFile('sservices', "Visibilities table already exists\n");
        }

        $tableNameInstallationMethods = 'glpi_plugin_sservices_installationmethods';
        if (!$DB->tableExists($tableNameInstallationMethods)) {
            Toolbox::logInFile('sservices', "Creating installation methods table: $tableNameInstallationMethods\n");
            $query = "CREATE TABLE `$tableNameInstallationMethods` (
                      `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                      `name` VARCHAR(255) NOT NULL,
                      `comment` text,
                      PRIMARY KEY  (`id`),
                      KEY `name` (`name`)
                   ) ENGINE=InnoDB  DEFAULT CHARSET=$defaultCharset COLLATE=$defaultCollation";
            $DB->doQuery($query) or die($DB->error());
            Toolbox::logInFile('sservices', "Installation methods table created successfully\n");
        } else {
            Toolbox::logInFile('sservices', "Installation methods table already exists\n");
        }

        Toolbox::logInFile('sservices', "Executing migration version $migrationVersion\n");
        $migration->executeMigration();
        $DB->insert($tableNameMigrations, [
            'version' => $migrationVersion,
        ]);
        Toolbox::logInFile('sservices', "Migration version $migrationVersion completed\n");
    } else {
        Toolbox::logInFile('sservices', "Migration version $migrationVersion already applied, skipping\n");
    }

    $migrationVersion = 200;
    $migration = new Migration($migrationVersion);
    if (!in_array($migrationVersion, $migratedVersions)) {
        Toolbox::logInFile('sservices', "Running migration version $migrationVersion\n");
        
        // Check if display preferences already exist before inserting
        $existingPref100 = $DB->request([
            'FROM' => 'glpi_displaypreferences',
            'WHERE' => [
                'itemtype' => 'GlpiPlugin\SServices\SService',
                'num' => 100
            ]
        ]);
        if (count($existingPref100) === 0) {
            $DB->insert('glpi_displaypreferences', [
                'itemtype' => 'GlpiPlugin\SServices\SService',
                'num' => 100,
                'rank' => 1,
            ]);
            Toolbox::logInFile('sservices', "Added display preference: num=100, rank=1\n");
        } else {
            Toolbox::logInFile('sservices', "Display preference num=100 already exists, skipping\n");
        }

        $existingPref200 = $DB->request([
            'FROM' => 'glpi_displaypreferences',
            'WHERE' => [
                'itemtype' => 'GlpiPlugin\SServices\SService',
                'num' => 200
            ]
        ]);
        if (count($existingPref200) === 0) {
            $DB->insert('glpi_displaypreferences', [
                'itemtype' => 'GlpiPlugin\SServices\SService',
                'num' => 200,
                'rank' => 2,
            ]);
            Toolbox::logInFile('sservices', "Added display preference: num=200, rank=2\n");
        } else {
            Toolbox::logInFile('sservices', "Display preference num=200 already exists, skipping\n");
        }

        $DB->insert($tableNameMigrations, [
            'version' => $migrationVersion,
        ]);
        Toolbox::logInFile('sservices', "Migration version $migrationVersion completed\n");
    } else {
        Toolbox::logInFile('sservices', "Migration version $migrationVersion already applied, skipping\n");
    }

    $migrationVersion = 300;
    $migration = new Migration($migrationVersion);
    if (!in_array($migrationVersion, $migratedVersions)) {
        Toolbox::logInFile('sservices', "Running migration version $migrationVersion\n");
        
        // Check if display preferences already exist before inserting
        $existingPref300 = $DB->request([
            'FROM' => 'glpi_displaypreferences',
            'WHERE' => [
                'itemtype' => 'GlpiPlugin\SServices\SService',
                'num' => 300
            ]
        ]);
        if (count($existingPref300) === 0) {
            $DB->insert('glpi_displaypreferences', [
                'itemtype' => 'GlpiPlugin\SServices\SService',
                'num' => 300,
                'rank' => 3,
            ]);
            Toolbox::logInFile('sservices', "Added display preference: num=300, rank=3\n");
        } else {
            Toolbox::logInFile('sservices', "Display preference num=300 already exists, skipping\n");
        }

        $existingPref400 = $DB->request([
            'FROM' => 'glpi_displaypreferences',
            'WHERE' => [
                'itemtype' => 'GlpiPlugin\SServices\SService',
                'num' => 400
            ]
        ]);
        if (count($existingPref400) === 0) {
            $DB->insert('glpi_displaypreferences', [
                'itemtype' => 'GlpiPlugin\SServices\SService',
                'num' => 400,
                'rank' => 4,
            ]);
            Toolbox::logInFile('sservices', "Added display preference: num=400, rank=4\n");
        } else {
            Toolbox::logInFile('sservices', "Display preference num=400 already exists, skipping\n");
        }

        $DB->insert($tableNameMigrations, [
            'version' => $migrationVersion,
        ]);
        Toolbox::logInFile('sservices', "Migration version $migrationVersion completed\n");
    } else {
        Toolbox::logInFile('sservices', "Migration version $migrationVersion already applied, skipping\n");
    }

    // Removing 'name' attribute
    $migrationVersion = 400;
    $migration = new Migration($migrationVersion);
    if (!in_array($migrationVersion, $migratedVersions)) {
        Toolbox::logInFile('sservices', "Running migration version $migrationVersion\n");
        
        if ($DB->tableExists($tableNameSServices)) {
            Toolbox::logInFile('sservices', "Dropping field 'name' from $tableNameSServices\n");
            $migration->dropField($tableNameSServices, 'name');
        }

        Toolbox::logInFile('sservices', "Deleting display preference num=200\n");
        $DB->delete(
            'glpi_displaypreferences',
            [
                'itemtype' => 'GlpiPlugin\SServices\SService',
                'num' => 200,
            ]
        );

        $migration->executeMigration();
        $DB->insert($tableNameMigrations, [
            'version' => $migrationVersion,
        ]);
        Toolbox::logInFile('sservices', "Migration version $migrationVersion completed\n");
    } else {
        Toolbox::logInFile('sservices', "Migration version $migrationVersion already applied, skipping\n");
    }

    Toolbox::logInFile('sservices', "=== SServices Plugin Installation Completed Successfully ===\n\n");
    return true;
}

/**
 * Uninstall hook
 *
 * @return boolean
 */
function plugin_sservices_uninstall(): bool
{
    Toolbox::logInFile('sservices', "=== Starting SServices Plugin Uninstallation ===\n");
    Toolbox::logInFile('sservices', "Uninstall operation disabled - tables and data will be preserved\n");
    Toolbox::logInFile('sservices', "=== SServices Plugin Uninstallation Completed ===\n\n");
    
    // For now, don't make any modifications if the plugin is uninstalled.
    // Below is code for deleting tables and plugin settings that can be enabled if needed.
    return true;

    global $DB;

    Toolbox::logInFile('sservices', "Starting database cleanup\n");

    $tablesToDrop = [
        'glpi_plugin_sservices_migrations',
        'glpi_plugin_sservices_sservices',
        'glpi_plugin_sservices_categories',
        'glpi_plugin_sservices_localusers',
        'glpi_plugin_sservices_visibilities',
        'glpi_plugin_sservices_installationmethods',
    ];

    foreach ($tablesToDrop as $tableName) {
        if ($DB->tableExists($tableName)) {
            Toolbox::logInFile('sservices', "Dropping table: $tableName\n");
            $query = "DROP TABLE `$tableName`";
            $DB->doQuery($query) or die($DB->error());
            Toolbox::logInFile('sservices', "Table $tableName dropped successfully\n");
        } else {
            Toolbox::logInFile('sservices', "Table $tableName does not exist, skipping\n");
        }
    }

    Toolbox::logInFile('sservices', "Deleting display preferences for SService\n");
    $DB->delete(
        'glpi_displaypreferences',
        ['itemtype' => ['LIKE', '%SService%']]
    );
    Toolbox::logInFile('sservices', "Display preferences deleted\n");

    Toolbox::logInFile('sservices', "=== SServices Plugin Uninstallation Completed Successfully ===\n\n");
    return true;
}

function sservices_redefine_menus($menu)
{
    return $menu;
}

/**
 * Define dropdown tables to be managed in GLPI
 */
function plugin_sservices_getDropdown(): array
{
    $plugin = new Plugin();
    if ($plugin->isActivated(SSERVICES_SSERVICES)) {
        return [
            Category::class => __("Category", SSERVICES_SSERVICES),
            LocalUser::class => __("Local User", SSERVICES_SSERVICES),
            Visibility::class => __("Visibility", SSERVICES_SSERVICES),
            InstallationMethod::class => __("Installation Method", SSERVICES_SSERVICES),
        ];
    }
    return [];
}

function plugin_sservices_getDatabaseRelations(): array
{
    return [
        'glpi_computers' => [
            'glpi_plugin_sservices_sservices' => 'computers_id'
        ],
        'glpi_plugin_sservices_categories' => [
            'glpi_plugin_sservices_sservices' => 'plugin_sservices_categories_id'
        ],
        'glpi_users' => [
            'glpi_plugin_sservices_sservices' => 'users_id'
        ],
        'glpi_groups' => [
            'glpi_plugin_sservices_sservices' => 'groups_id'
        ],
        'glpi_plugin_sservices_localusers' => [
            'glpi_plugin_sservices_sservices' => 'plugin_sservices_localusers_id'
        ],
        'glpi_plugin_sservices_visibilities' => [
            'glpi_plugin_sservices_sservices' => 'plugin_sservices_visibilities_id'
        ],
        'glpi_plugin_sservices_installationmethods' => [
            'glpi_plugin_sservices_sservices' => 'plugin_sservices_installationmethods_id'
        ],
    ];
}

/**
 * Override for setting link to individual service
 */
function plugin_sservices_giveItem($type, $ID, $data, $num): string
{
    $searchopt = &Search::getOptions($type);
    $table = $searchopt[$ID]["table"];
    $field = $searchopt[$ID]["field"];

    switch ($table . '.' . $field) {
        case "glpi_plugin_sservices_categories.name":
            $out = "<a href='" . Toolbox::getItemTypeFormURL(SService::class) . "?id=" . $data['id'] . "'>";
            $out .= $data[$num][0]['name'];
            $out .= "</a>";
            return $out;
    }
    return "";
}

/**
 * Processing before purging an item (e.g., for Computer)
 */
function sservices_pre_item_purge(CommonDBTM $item): void
{
    $tempSService = new SService();

    if ($item::getType() === Computer::getType()) {
        $computerId = $item->getField('id');

        if (!empty($computerId)) {
            Toolbox::logInFile('sservices', "Pre-purge: Permanently deleting services for Computer ID: $computerId\n");
            $tempSService->deleteByCriteria(
                [
                    'computers_id' => $computerId,
                ],
                1
            );
            Toolbox::logInFile('sservices', "Pre-purge: Services deleted for Computer ID: $computerId\n");
        }
    }
}

/**
 * Processing before deleting an item (moving to Trash Bin)
 */
function sservices_pre_item_delete(CommonDBTM $item): void
{
    $tempSService = new SService();

    if ($item::getType() === Computer::getType()) {
        $computerId = $item->getField('id');

        if (!empty($computerId)) {
            Toolbox::logInFile('sservices', "Pre-delete: Moving services to trash for Computer ID: $computerId\n");
            $tempSService->deleteByCriteria([
                'computers_id' => $computerId,
            ]);
            Toolbox::logInFile('sservices', "Pre-delete: Services moved to trash for Computer ID: $computerId\n");
        }
    }
}

/**
 * Processing before restoring an item from Trash Bin
 */
function sservices_pre_item_restore(CommonDBTM $item): void
{
    $tempSService = new SService();
    global $DB;

    if ($item::getType() === Computer::getType()) {
        $computerId = $item->getField('id');
        if (!empty($computerId)) {
            Toolbox::logInFile('sservices', "Pre-restore: Restoring services for Computer ID: $computerId\n");
            
            $criteria = [
                'FIELDS' => [$tempSService::getTable() => 'id'],
                'computers_id' => $computerId,
            ];
            $iterator = $DB->request($tempSService::getTable(), $criteria);

            $restoredCount = 0;
            foreach ($iterator as $row) {
                $tempSService->restore($row);
                $restoredCount++;
            }
            
            Toolbox::logInFile('sservices', "Pre-restore: $restoredCount service(s) restored for Computer ID: $computerId\n");
        }
    }
}