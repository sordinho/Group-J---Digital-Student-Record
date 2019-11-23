<?php


require_once("../../config.php");


$site = new csite();
initialize_site($site);
$page = new cpage("Attendance");
$site->setPage($page);
$sparent = new sparent();

if (!$sparent->is_logged() || $sparent->get_parent_ID() == -1) {
    $content = '
    <div class="alert alert-warning" role="warning">
        You are not authorized. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
    </div> ';
    $content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
    $page->setContent($content);
    $site->render();
    exit();
} else {
    // todo
}

$page->setContent($content);
$site->render();