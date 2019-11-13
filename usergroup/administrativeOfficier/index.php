<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Administrative Officier homepage");
$site->setPage($page);

$content = "<div class='container article-clean'>TODO</div>";

$page->setContent($content);
$site->render();