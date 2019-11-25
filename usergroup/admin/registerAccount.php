<?php

require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Account Registration");
$site->setPage($page);

$administrator = new administrator();

if (!$administrator->is_logged() || !$administrator->is_admin()) {
	$content = '
    <div class="alert alert-warning" role="warning">
        You are not authorized. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
    </div> ';
	$content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
	$page->setContent($content);
	$site->render();
	exit();
}

$content ='';

$page->setContent($content);
$site->render();