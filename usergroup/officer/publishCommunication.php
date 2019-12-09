<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Publish new communication");
$site->setPage($page);
$officer = new officer();

if(!$officer ->is_logged() ){
    header("location: /error.php?errorID=19");
    exit();
}

$content=<<<OUT

OUT;


$page->setContent($content);
$site->render();