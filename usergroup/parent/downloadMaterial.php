<?php


require_once("../../config.php");


$site = new csite();
initialize_site($site);
$page = new cpage("Download Materials");
$site->setPage($page);
$sparent = new sparent();

if (!$sparent->is_logged()) {
    header("location: /error.php?errorID=19");
    exit();
}

// TODO check if child was selected

# Declare the div where the calendar will be injected, include dependency and custom js script to handle calendar

if ($handle = opendir('../../files/')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            $content .= "<a href=\"download.php?file=".$entry."\">$entry</a><br>";
        }
    }
    closedir($handle);
}

$page->setContent($content);
$site->render();