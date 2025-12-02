<?php

include ("../bootstrap.php");

use GlpiPlugin\SServices\SService;

global $CFG_GLPI;

// Check if plugin is activated...
$plugin = new Plugin();

if (!$plugin->isInstalled(SSERVICES_SSERVICES) || !$plugin->isActivated(SSERVICES_SSERVICES)) {
    Html::displayNotFoundError();
}

$sservice = new SService();

if (isset($_POST['add'])) {
    //Check CREATE ACL
    $sservice->check(-1, CREATE, $_POST);
    //Do object creation
    $newid = $sservice->add($_POST);
    //Redirect to newly created object form
    Html::redirect("{$CFG_GLPI['root_doc']}/plugins/sservices/front/sservice.form.php?id=$newid");
} else if (isset($_POST['update'])) {
    //Check UPDATE ACL
    //$sservice->check($_POST['id'], UPDATE);
    //Do object update
    $sservice->update($_POST);
    //Redirect to object form
    Html::back();
} else if (isset($_POST['delete'])) {
    //Check DELETE ACL
    //$sservice->check($_POST['id'], DELETE);
    //Put object in dustbin
    $sservice->delete($_POST);
    //Redirect to objects list
    $sservice->redirectToList();
} else if (isset($_POST['restore'])) {
    //Check DELETE ACL
    //$sservice->check($_POST['id'], DELETE);
    //Put object in dustbin
    $sservice->restore($_POST);
    //Redirect to objects list
    Html::redirect("{$CFG_GLPI['root_doc']}/plugins/sservices/front/sservice.form.php?id={$_POST['id']}");
} else if (isset($_POST['purge'])) {
    //Check PURGE ACL
    //$sservice->check($_POST['id'], PURGE);
    //Do object purge
    $sservice->delete($_POST, 1);
    //Redirect to objects list
    Html::redirect("{$CFG_GLPI['root_doc']}/plugins/sservices/front/sservice.php");
} else {
    Html::header(
        __("SServices", SSERVICES_SSERVICES),
        $_SERVER['PHP_SELF'],
        "assets",
        SService::class,
        SSERVICES_SSERVICES
    );

    //per default, display object
    $withtemplate = (isset($_GET['withtemplate']) ? $_GET['withtemplate'] : 0);
//    $sservice->display(
//        [
//            'id'           => $_GET['id'],
//            'withtemplate' => $withtemplate
//        ]
//    );
    $sservice->display([
            'id'           => -1,
            'withtemplate' => $withtemplate,
            'formtitle' => 'SService',
            'computers_id' => $_GET['computers_id'] ?? null
        ]);

    Html::footer();
}