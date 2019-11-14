<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Register new parent");
$site->setPage($page);
$officier = new officer();
if(!$officier->get_officer_ID()){
    $content = '
    <div class="alert alert-warning" role="warning">
        You are not authorized. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
    </div> ';
    $content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
    $page->setContent($content);
    $site->render();
    render_page($content, '');
}// should call get_error TODOs:
$num = 1;
$content="";

if($_GET["action"] != "activate"){
    $content = '
    <!-- The container  -->
    <div class="container article-clean">
        <div class="wrapper">
            <br/>
            <h1>Batch parent activation process</h1>
            <p class="lead">Click on the button to generate the parent authentication data and send them by mail.<br></p>
            <a class="btn btn-primary" href="./batchActivateAuthentication.php?action=activate"role="button">Parent activation</a>
        </div>
    </div>';
}else{
}
$page->setContent($content);
$site->render();
