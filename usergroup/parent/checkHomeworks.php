<?php


require_once("../../config.php");


$site = new csite();
initialize_site($site);
$page = new cpage("Homeworks");
$site->setPage($page);
$sparent = new sparent();

if (!$sparent->is_logged() || $sparent->get_parent_ID() == -1) {
    $content = '
    <div class="alert alert-warning" role="warning">
        You are not authorized. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
    </div> ';
    $content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
    $page->setContent($content);
    $site->render();
    exit();
}

$homework_info = $sparent->get_homeworks($sparent->get_current_child());
/*foreach ($homework_info as $homework) {
    var_dump($homework);
}*/

# Declare the div where the calendar will be injected, include dependency and custom js script to handle calendar
$content = '<div id="calendar"></div>
            <script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>';
$content .='<script src="'.PLATFORM_PATH.'/js/calendar.js"></script>';

$page->setContent($content);
$site->render();