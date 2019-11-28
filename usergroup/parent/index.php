<?php

require_once("../../config.php");


$site = new csite();
initialize_site($site);
$page = new cpage("Parent");
$site->setPage($page);
$sparent = new sparent();

if (!$sparent->is_logged() || $sparent->get_parent_ID() == -1) {
	header("location: /error.php?errorID=19");
	exit();
}


if ($_GET['action'] == "switchChild") {
	$new_childID = intval($_GET["childID"]);
	$sparent->set_current_child($new_childID);
	$content = '<div class="alert alert-success" role="alert">You just switched student<br>In a few seconds you will be redirected to home. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a></div>';
	$content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . $_SESSION["base_url"] . "' />";
} else {
	// If no child was choosen show an alert
	if ($sparent->get_current_child() == -1) {
		$hidden_warning = '
			<div class="alert alert-warning alert-dismissible fade show" role="alert">
			  <h4 class="alert-heading">Hello!</h4>
			  <p>Please, <strong>select a student</strong> you want to operate on from the sidebar.</p>
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
			  <hr>
			  <p class="mb-0">After selected a student, you can access all the functionalities in the sidebar.</p>
			</div>';
	}
	else {
		$content_after_selected_child = '<p>On the sidebar you can quickly perform many different actions.</p>';
	}
	$content = '<div class="container article-clean">
                  <div class="row">
                      <div class="col-lg-10 col-xl-8 offset-lg-1 offset-xl-2">
                          <div class="text-center intro">
                              <h1 class="text-center">What can i do?</h1>
                              '.$content_after_selected_child.'
                              <p class="text-center"><span class="by"></span> <a href="#"></a><span class="date"></span></p><!--<img class="img-fluid" src="assets/img/desk.jpg">--></div>
                          <div class="text">
                              <p>' . $hidden_warning . '</p>
                          </div>
                      </div>
                  </div>
                  </div>';
}

$page->setContent($content);
$site->render();
?>
