<?php

//include ('../../../inc/includes.php');

include ('../bootstrap.php');

// Check if plugin is activated...
$plugin = new Plugin();

if (!$plugin->isInstalled(SSERVICES_SSERVICES) || !$plugin->isActivated(SSERVICES_SSERVICES)) {
    Html::displayNotFoundError();
}

use GlpiPlugin\SServices\InstallationMethod;

$dropdown = new InstallationMethod();

include (GLPI_ROOT . "/front/dropdown.common.form.php");
