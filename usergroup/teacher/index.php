<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("");
$site->setPage($page);
$teacher= new teacher();


if(!$teacher ->is_logged() ){
	header("location: /error.php?errorID=19");
	exit();
}

//$content ='<a href="usergroup/teacher/teacherAction1ToMove.php">Action1To incorporate in MENU</a>';
$content = '

<div class="card">
    <h2 style="background-color:rgba(108,108,108,0.9);color:white" class="card-header info-color white-text text-center py-4">
        <strong>Professor '.$teacher->get_surname().' '.$teacher->get_name().'</strong>
    </h2>
    
    <div class="card-body px-lg-5 pt-0">
    <form>
		<div class="form-row">
			<p class="text-center"><h1 class="text-center">On the sidebar you can quickly perform many different actions.</h1></p>
		</div>
	</form>
	</div>
</div>';
$page->setContent($content);
$site->render();

?>
