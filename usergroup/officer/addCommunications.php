<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("");
$site->setPage($page);

$officer = new officer();

if(!$officer ->is_logged() ){
    header("location: /error.php?errorID=19");
    exit();
}
