<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("");
$site->setPage($page);
$teacher= new teacher();


if(!$teacher ->is_logged() ){
	$teacher->get_error(19);
	exit();
}

//$content ='<a href="usergroup/teacher/teacherAction1ToMove.php">Action1To incorporate in MENU</a>';
$content = '

<div class="card">
    <h2 style="background-color:rgba(108,108,108,0.9);color:white" class="card-header info-color white-text text-center py-4">
        Logged as: Prof. '.$teacher->get_name().' '.$teacher->get_surname().'
    </h2>
    
    <div class="row justify-content-lg-center card-body">
		<div class="card-body p-lg-5">
			<div class="card" style="width: 15rem;">
			  <div class="card-body">
				<h4 class="card-title">Where to start?</h4>
				<h5 class="card-subtitle mb-2 text-muted">Find a functionality</h5>
				<p class="card-text">On the sidebar you can perform different actions.</p>
			  </div>
			</div>
		</div>
		
		<div class="card-body p-lg-5">
			<div class="card" style="width: 15rem;">
			  <div class="card-body">
				<h4 class="card-title">Feeling lost?</h4>
				<h5 class="card-subtitle mb-2 text-muted">Choose your role</h5>
				<p class="card-text">Are you sure you selected the right role during log in?</p>
			  </div>
			</div>
		</div>
		
		<div class="card-body p-lg-5">
			<div class="card" style="width: 15rem;">
			  <div class="card-body">
				<h4 class="card-title">In a hurry?</h4>
				<h5 class="card-subtitle mb-2 text-muted">Work on the go</h5>
				<p class="card-text">Feel free to use the system from your smartphone.</p>
			  </div>
			</div>
		</div>
	</div>
</div>';
$page->setContent($content);
$site->render();

?>
