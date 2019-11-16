<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Administrative Officer homepage");
$site->setPage($page);

$content = "<div class='container article-clean'>Administrative Officer</div>
            <a href=\"studentEnrollment.php\" class=\"badge badge-secondary\">Student Enrollment Page</a>
            <a href=\"classCompositionModification.php\" class=\"badge badge-secondary\">Class Composition Modification Page</a>";

$page->setContent($content);
$site->render();