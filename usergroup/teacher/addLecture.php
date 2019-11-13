<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Teacher homepage");
$site->setPage($page);
$teacher = new Teacher();

if(!$teacher ->is_logged()){
	$content = '
    <div class="alert alert-warning" role="warning">
        You are not authorized. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
    </div> ';
	$content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
	$page->setContent($content);
	$site->render();
    render_page($content, '');
}


if(!isset($_POST["lDescription"])){
//$content ='<a href="usergroup/teacher/teacherAction1ToMove.php">Action1To incorporate in MENU</a>';
	$content = '<div class="container article-clean">
	<div class="row">
	<form>
		<div class="form-group">
		<label for="Title">Title</label>
		<input type="text" class="form-control" name="title" id="title" placeholder="Course Introduction">
		</div>
		<div class="form-group">
		<label for="exampleFormControlSelect2">Topic multiple select</label>
		<select multiple class="form-control" name="topicID id="topicID">
			<option>Topic1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
			<option>5</option>
		</select>
		</div>
		<div class="form-group">
		<label for="exampleFormControlTextarea1">Description</label>
		<textarea class="form-control" id="description" name="description rows="3"></textarea>
		</div>
	</form>
	</div>
	</div>';
}

$page->setContent($content);
$site->render();

?>
