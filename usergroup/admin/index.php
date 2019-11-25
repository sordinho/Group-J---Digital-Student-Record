<?php

require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Administrator");
$site->setPage($page);

$administrator = new administrator();

if (!$administrator->is_logged() || !$administrator->is_admin()) {
	$content = '
    <div class="alert alert-warning" role="warning">
        You are not authorized. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
    </div> ';
	$content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
	$page->setContent($content);
	$site->render();
	exit();
}

$content = '<div class="container article-clean">
                  <div class="row">
                      <div class="col-lg-10 col-xl-8 offset-lg-1 offset-xl-2">
                          <div class="text-center intro">
                              <h1 class="text-center">Welcome, System Administrator</h1>
                              <p class="text-center"><span class="by"></span> <a href="#"></a><span class="date"></span></p><!--<img class="img-fluid" src="assets/img/desk.jpg">--></div>
           			      </div>
                  	  </div>
                  </div>
             </div>';
$page->setContent($content);
$site->render();
?>