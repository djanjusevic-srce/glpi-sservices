<?php

//include ('../../../inc/includes.php');

include ('../bootstrap.php');

// Check if plugin is activated...
$plugin = new Plugin();

if (!$plugin->isInstalled(SSERVICES_SSERVICES) || !$plugin->isActivated(SSERVICES_SSERVICES)) {
    Html::displayNotFoundError();
}

use GlpiPlugin\SServices\SService;

//check for ACLs
if (SService::canView()) {
    //View is granted: display the list.

    //Add page header
    Html::header(
        __('SServices plugin', SSERVICES_SSERVICES),
        $_SERVER['PHP_SELF'],
        'assets',
        SService::class,
    );

    Search::show(SService::class);

    Html::footer();
} else {
    //View is not granted.
    Html::displayRightError();
}