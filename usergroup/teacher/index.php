<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Teacher Home");
$site->setPage($page);
$teacher= new teacher();


if(!$teacher ->is_logged() || $teacher ->get_teacher_ID()==-1){
	$content = '
    <div class="alert alert-warning" role="warning">
        You are not authorized. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
    </div> ';
	$content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
	$page->setContent($content);
	$site->render();
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
			<p>On the sidebar you can quickly perform many different actions. <span style="text-decoration: underline;">Vivamus</span> ac sem lac. Ut vehicula rhoncus elementum. Etiam quis tristique lectus. Aliquam in arcu eget velit pulvinar dictum vel in justo.
				Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.</p>
			<p>Praesent sed lobortis mi. Suspendisse vel placerat ligula. Vivamus ac lacus. <strong>Ut vehicula rhoncus</strong> elementum. Etiam quis tristique lectus. Aliquam in arcu eget velit <em>pulvinar dict</em> vel in justo. Vestibulum ante
				ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.</p>
			<h2>Aliquam In Arcu </h2>
			<p>Suspendisse vel placerat ligula. Vivamus ac sem lac. Ut vehicula rhoncus elementum. Etiam quis tristique lectus. Aliquam in arcu eget velit pulvinar dictum vel in justo. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices
				posuere cubilia Curae.</p>
			<p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae. Suspendisse vel placerat ligula. Vivamus ac sem lac. Ut vehicula rhoncus elementum. Etiam quis tristique lectus. Aliquam in arcu eget velit pulvinar
				dictum vel in justo.</p>
		</div>
	</div>
</div>
</div>';
$page->setContent($content);
$site->render();

?>
