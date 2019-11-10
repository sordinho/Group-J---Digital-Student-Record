<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Administrative Officier homepage");
$site->setPage($page);

/*here content*/

//todo

$page->setContent($content);
$site->render();
