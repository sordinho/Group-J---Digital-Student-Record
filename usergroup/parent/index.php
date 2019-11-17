<?php

require_once("../../config.php");

$parentObj=new sparent();

$site = new csite();
initialize_site($site);
$page = new cpage("Parent");
$site->setPage($page);
$sparent = new sparent($_SESSION);


if($_GET['action'] == "switchChild" && $sparent->is_logged()){// is_logged should extend the base in user
  $new_childID = intval($_GET["childID"]);
  $sparent->set_current_child($new_childID);
  $content = '<div class="alert alert-success" role="alert">
    You just switched child<br>In a few seconds you will be redirected to home. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
  </div>';
  $content .= "<meta http-equiv='refresh' content='2; url=" . $_SESSION["base_url"]. "' />";

}
else{
// Should be moved to other page and just linked in the menu
$hidden_warning = ""; 

// If no child was choosen show an alert
if($sparent->get_current_child() == -1){
  $hidden_warning = '<div class="alert alert-warning" role="alert">
    Please select on which child you want to operate on from the sidebar.
  </div>';
}
$content = '<div class="container article-clean">
<div class="row">
	<div class="col-lg-10 col-xl-8 offset-lg-1 offset-xl-2">
		<div class="text-center intro">
			<h1 class="text-center">What can i do?</h1>
			<p class="text-center"><span class="by"></span> <a href="#"></a><span class="date"></span></p><!--<img class="img-fluid" src="assets/img/desk.jpg">--></div>
		<div class="text">
			<p>'.$hidden_warning.'</p>
		</div>
	</div>
</div>
</div>';


}

$page->setContent($content);
$site->render();
?>
