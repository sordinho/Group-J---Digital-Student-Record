<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Teacher Home");
$site->setPage($page);
$teacher= new teacher();


if(!$teacher ->is_logged() ){
	header("location: /error.php?errorID=19");
	exit();
}

//$content ='<a href="usergroup/teacher/teacherAction1ToMove.php">Action1To incorporate in MENU</a>';
$content = '<div class="container article-clean">
<div class="row">
	<div class="col-lg-10 col-xl-8 offset-lg-1 offset-xl-2">
		<div class="text-center intro">
			<h1 class="text-center">What can i do?</h1>
			<p class="text-center"><span class="by"></span> <a href="#"></a><span class="date"></span></p><!--<img class="img-fluid" src="assets/img/desk.jpg">--></div>
		<div class="text">
			<p class="text-center">On the sidebar you can quickly perform many different actions.</p>
		</div>
	</div>
</div>
</div>';
$page->setContent($content);
$site->render();

?>
