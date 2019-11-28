<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Administrative Officer Home");
$site->setPage($page);

$officer = new officer();

if(!$officer ->is_logged() || $officer ->get_officer_ID()==-1){
	header("location: /error.php?errorID=19");
	exit();
}

$content = "";

$page->setContent($content);
$site->render();