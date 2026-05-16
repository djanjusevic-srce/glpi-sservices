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
    //instance migration with version
    $migration = new Migration($migrationVersion);

//    $defaultCharset = DBConnection::getDefaultCharset();
//    $defaultCollation = DBConnection::getDefaultCollation();
//    $defaultKeySign = DBConnection::getDefaultPrimaryKeySignOption();

    $tableNameMigrations = 'glpi_plugin_sservices_migrations';
    if (!$DB->tableExists($tableNameMigrations)) {
	Toolbox::logInFile('sservices', "Creating migrations table $tableNameMigrations\n");
        $DB->runFile(GLPI_ROOT . '/plugins/sservices/install/mysql/glpi_plugin_sservices_migrations.sql');
	Toolbox::logInFile('sservices', "Migrations table $tableNameMigrations created successfully\n");
    }

    $migratedVersions = [];

    foreach ($DB->request($tableNameMigrations) as $row) {
        $migratedVersions[] = (int)$row['version'];
    }
    Toolbox::logInFile('sservices', "Previously migrated versions: " . implode(', ', $migratedVersions) . "\n");

    $tableNameSServices = 'glpi_plugin_sservices_sservices';
    if (!in_array($migrationVersion, $migratedVersions)) {
	Toolbox::logInFile('sservices', "Running migration version $migrationVersion\n");
        //Create table only if it does not exist yet!
	if (!$DB->tableExists($tableNameSServices)) {
	    Toolbox::logInFile('sservices', "Creating table: $tableNameSServices\n");
            $DB->runFile(GLPI_ROOT . '/plugins/sservices/install/mysql/glpi_plugin_sservices_sservices.sql');
            Toolbox::logInFile('sservices', "Table $tableNameSServices created successfully\n");
	} else {
	    Toolbox::logInFile('sservices', "Table $tableNameSServices already exists\n");
	}

        if ($DB->tableExists($tableNameSServices)) {
            Toolbox::logInFile('sservices', "Adding fields to $tableNameSServices\n");
            //missed value for is_deleted
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
            Toolbox::logInFile('sservices', "Creating table $tableNameCategories\n");
            $DB->runFile(GLPI_ROOT . '/plugins/sservices/install/mysql/glpi_plugin_sservices_categories.sql');
            Toolbox::logInFile('sservices', "Table $tableNameCategories created successfully\n");
            //Toolbox::logInFile('sservices', "Seeding table $tableNameCategories with predefined content\n");
            //$DB->runFile(GLPI_ROOT . '/plugins/sservices/install/mysql/seed_glpi_plugin_sservices_categories.sql');
            //Toolbox::logInFile('sservices', "Table $tableNameCategories seeded successfully\ni");
        } else {
            Toolbox::logInFile('sservices', "Table $tableNameCategories already exists\n");
        }


        $tableNameLocalUsers = 'glpi_plugin_sservices_localusers';
        if (!$DB->tableExists($tableNameLocalUsers)) {
            Toolbox::logInFile('sservices', "Creating table $tableNameLocalUsers\n");
            $DB->runFile(GLPI_ROOT . '/plugins/sservices/install/mysql/glpi_plugin_sservices_localusers.sql');
            Toolbox::logInFile('sservices', "Table $tableNameLocalUsers created successfully\n");
            //Toolbox::logInFile('sservices', "Seeding table $tableNameLocalUsers with predefined content\n");
            //$DB->runFile(GLPI_ROOT . '/plugins/sservices/install/mysql/seed_glpi_plugin_sservices_localusers.sql');
            //Toolbox::logInFile('sservices', "Table $tableNameLocalUsers seeded successfully\n");
        } else {
            Toolbox::logInFile('sservices', "Table $tableNameLocalUsers already exists\n");
        }

        $tableNameVisibilities = 'glpi_plugin_sservices_visibilities';
        if (!$DB->tableExists($tableNameVisibilities)) {
            Toolbox::logInFile('sservices', "Creating table $tableNameVisibilities\n");
            $DB->runFile(GLPI_ROOT . '/plugins/sservices/install/mysql/glpi_plugin_sservices_visibilities.sql');
            Toolbox::logInFile('sservices', "Table $tableNameVisibilities created successfully\n");
            Toolbox::logInFile('sservices', "Seeding table $tableNameVisibilities with predefined content\n");
            $DB->runFile(GLPI_ROOT . '/plugins/sservices/install/mysql/seed_glpi_plugin_sservices_visibilities.sql');
            Toolbox::logInFile('sservices', "Table $tableNameVisibilities seeded successfully\n");
        } else {
            Toolbox::logInFile('sservices', "Table $tableNameVisibilities already exists\n");
        }

        $tableNameInstallationMethods = 'glpi_plugin_sservices_installationmethods';
        if (!$DB->tableExists($tableNameInstallationMethods)) {
            Toolbox::logInFile('sservices', "Creating table $tableNameInstallationMethods\n");
            $DB->runFile(GLPI_ROOT . '/plugins/sservices/install/mysql/glpi_plugin_sservices_installationmethods.sql');
            Toolbox::logInFile('sservices', "Table $tableNameInstallationMethods created successfully\n");
            Toolbox::logInFile('sservices', "Seeding table $tableNameInstallationMethods with predefined content\n");
            $DB->runFile(GLPI_ROOT . '/plugins/sservices/install/mysql/seed_glpi_plugin_sservices_installationmethods.sql');
            Toolbox::logInFile('sservices', "Table $tableNameInstallationMethods seeded successfully\n");
        } else {
            Toolbox::logInFile('sservices', "Table $tableNameInstallationMethods already exists\n");
        }

        Toolbox::logInFile('sservices', "Executing migration version $migrationVersion\n");
        //execute the whole migration
        $migration->executeMigration();
        $migration->insertInTable($tableNameMigrations, [
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

	$migration->insertInTable('glpi_displaypreferences', [
            'itemtype' => 'GlpiPlugin\SServices\SService',
            'num' => 100,
            'rank' => 1,
        ]);
	Toolbox::logInFile('sservices', "Added display preference: num=100, rank=1\n");

	$migration->insertInTable('glpi_displaypreferences', [
            'itemtype' => 'GlpiPlugin\SServices\SService',
            'num' => 200,
            'rank' => 2,
        ]);
        Toolbox::logInFile('sservices', "Added display preference: num=200, rank=2\n");

	$migration->insertInTable($tableNameMigrations, [
            'version' => $migrationVersion,
        ]);
        Toolbox::logInFile('sservices', "Migration version $migrationVersion completed\n");
    }

    $migrationVersion = 300;
    $migration = new Migration($migrationVersion);
    if (!in_array($migrationVersion, $migratedVersions)) {
        Toolbox::logInFile('sservices', "Running migration version $migrationVersion\n");

	$migration->insertInTable('glpi_displaypreferences', [
            'itemtype' => 'GlpiPlugin\SServices\SService',
            'num' => 300,
            'rank' => 3,
        ]);
        Toolbox::logInFile('sservices', "Added display preference: num=300, rank=3\n");

        $migration->insertInTable('glpi_displaypreferences', [
            'itemtype' => 'GlpiPlugin\SServices\SService',
            'num' => 400,
            'rank' => 4,
        ]);
        Toolbox::logInFile('sservices', "Added display preference: num=400, rank=4\n");

        $migration->insertInTable($tableNameMigrations, [
            'version' => $migrationVersion,
        ]);
        Toolbox::logInFile('sservices', "Migration version $migrationVersion completed\n");
    }

    // Removing 'name' attribute
    $migrationVersion = 400;
    $migration = new Migration($migrationVersion);
    if (!in_array($migrationVersion, $migratedVersions)) {
        Toolbox::logInFile('sservices', "Running migration version $migrationVersion\n");
        if ($DB->tableExists($tableNameSServices)) {
            Toolbox::logInFile('sservices', "Dropping field 'name' from $tableNameSServices\n");
            $DB->runFile(GLPI_ROOT . '/plugins/sservices/install/mysql/drop_sservices_name_column.sql');
            Toolbox::logInFile('sservices', "Dropped field 'name' from $tableNameSServices\n");
        }

        Toolbox::logInFile('sservices', "Deleting display preference num=200\n");
        $DB->delete(
            'glpi_displaypreferences',
            [
                'itemtype' => 'GlpiPlugin\SServices\SService',
                'num' => 200,
            ]
        );

        //execute the whole migration
        $migration->executeMigration();
        $migration->insertInTable($tableNameMigrations, [
            'version' => $migrationVersion,
        ]);
	Toolbox::logInFile('sservices', "Migration version $migrationVersion completed\n");
    }

    return true;
}

/**
 * Uninstall hook
 *
 * @return boolean
 */
function plugin_sservices_uninstall(): bool
{

    // Uncomment 2 lines below to disable any modifications to tables and data when the plugin is uninstalled.
    //Toolbox::logInFile('sservices', "Uninstall operation disabled - tables and data will be preserved\n");
    //return true;

    // Code for deleting tables and plugin settings is below.
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
            $DB->dropTable($tableName);
            Toolbox::logInFile('sservices', "Table $tableName dropped successfully\n");
	} else {
	    Toolbox::logInFile('sservices', "Table $tableName does not exist, skipping\n");
    }

    Toolbox::logInFile('sservices', "Deleting display preferences for SService\n");
    $DB->delete(
        'glpi_displaypreferences',
        [
            'itemtype' => 'GlpiPlugin\\SServices\\SService'
        ]
    );
    Toolbox::logInFile('sservices', "Display preferences deleted\n");

    Toolbox::logInFile('sservices', "=== SServices Plugin Uninstallation Completed Successfully ===\n\n");
    return true;
}

function sservices_redefine_menus($menu) 
{
//    unset($menu['assets'][0]);
//    die(var_dump($menu['assets']));
    return $menu;
}

/**
 * Define dropdown tables to be managed in GLPI
 */
function plugin_sservices_getDropdown() 
{
    /* table => name */
    $plugin = new Plugin();
    if ($plugin->isActivated(SSERVICES_SSERVICES)) {
        return [
            Category::class => __("Category", SSERVICES_SSERVICES),
            LocalUser::class => __("Local User", SSERVICES_SSERVICES),
            Visibility::class => __("Visibility", SSERVICES_SSERVICES),
            InstallationMethod::class => __("Installation Method", SSERVICES_SSERVICES),
        ];
    } else {
        return [];
    }
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
 * Override for setting link to individual service (otherwise link is only on ID attribute)
 * @param $type
 * @param $ID
 * @param $data
 * @param $num
 * @return string
 */
function plugin_sservices_giveItem($type, $ID, $data, $num): string
{
    $searchopt = &Search::getOptions($type);
    $table = $searchopt[$ID]["table"];
    $field = $searchopt[$ID]["field"];

    switch ($table . '.' . $field) {
        case "glpi_plugin_sservices_categories.name" :
            $out = "<a href='" . Toolbox::getItemTypeFormURL(SService::class) . "?id=" . $data['id'] . "'>";
            $out .= $data[$num][0]['name'];
            $out .= "</a>";
            return $out;
    }
    return "";
}

/**
 * Processing before purging an item
 * @param CommonDBTM $item
 * @return void
 */
function sservices_pre_item_purge(CommonDBTM $item): void
{
    $tempSService = new SService();

    if ($item::getType() === Computer::getType()) {
        $computerId = $item->getField('id');

        if (!empty($computerId)) {
            $tempSService->deleteByCriteria(
                [
                    'computers_id' => $computerId,
                ],
                1
            );
	    Toolbox::logInFile('sservices', "Service(s) purged from trash for Computer ID: {$computerId}\n");
        }
    }
}

/**
 * Processing before deleting an item - putting into Trash Bin
 * @param CommonDBTM $item
 * @return void
 */
function sservices_pre_item_delete(CommonDBTM $item): void
{
    global $DB;

    if ($item::getType() !== Computer::getType()) {
        return;
    }

    $computerId = $item->getField('id');

    if (empty($computerId)) {
        return;
    }

    $DB->update(
        SService::getTable(),
        [
            'is_deleted' => 1,
        ],
        [
            'computers_id' => $computerId,
        ]
    );

    Toolbox::logInFile('sservices', "Service(s) moved to trash for Computer ID: {$computerId}\n");
}

/**
 * Processing before restoring an item from Trash Bin
 * @param CommonDBTM $item
 * @return void
 */
function sservices_pre_item_restore(CommonDBTM $item): void
{
    global $DB;

    if ($item::getType() !== Computer::getType()) {
        return;
    }

    $computerId = $item->getField('id');

    if (empty($computerId)) {
        return;
    }

    $DB->update(
        SService::getTable(),
        [
            'is_deleted' => 0,
        ],
        [
            'computers_id' => $computerId,
        ]
    );

    Toolbox::logInFile('sservices', "Restored service(s) for computer ID {$computerId}");
}

