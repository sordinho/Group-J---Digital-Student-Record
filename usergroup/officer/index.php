<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Administrative Officer Home");
$site->setPage($page);


$content = "<div class='container article-clean'>Administrative Officer</div>
            <a href=\"classCompositionModification.php\" class=\"badge badge-secondary\">Class Composition Modification Page</a>";

$page->setContent($content);
$site->render();