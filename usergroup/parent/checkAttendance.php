<?php


require_once("../../config.php");


$site = new csite();
initialize_site($site);
$sparent = new sparent();
$children = $sparent->get_children_info();
$childID = $sparent->get_current_child();
$key = array_search($childID, array_column($children, 'StudentID'));
$page_title = "Attendance of " . $children[$key]['Name'] . " " . $children[$key]['Surname'];
$page = new cpage($page_title);
$site->setPage($page);


if (!$sparent->is_logged()) {
	header("location: /error.php?errorID=19");
	exit();
}
// TD check if child was selected 

$content = '<div id="calendar"></div>
<script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>';
$content .='<script src="'.PLATFORM_PATH.'/js/presencecalendar.js"></script>';



$page->setContent($content);
$site->render();