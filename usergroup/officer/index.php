<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Administrative Officer Home");
$site->setPage($page);

$content = "<div class='container article-clean'>Administrative Officer</div>";

$page->setContent($content);
$site->render();