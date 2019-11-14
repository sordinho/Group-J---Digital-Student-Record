<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Add lecture");
$site->setPage($page);
$teacher = new teacher();

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


if(!isset($_POST["description"])){
//$content ='<a href="usergroup/teacher/teacherAction1ToMove.php">Action1To incorporate in MENU</a>';
	$content = '<div class="container article-clean">
	<div class="row">
	<form method="POST">
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
		<textarea class="form-control" id="description" name="description" rows="3"></textarea>
		</div>
		<div class="form-group">
		<label for="exampleFormControlTextarea1">Date</label>
		<input type="date" id="date" name="date">
		</div>
		
		
	</form>
	</div>
	</div>';
} else{
	print("Now we should insert the topic");
	//$teacher->$insert_new_lecture_topic(lectureDescription,topicRecordID, );
}

$page->setContent($content);
$site->render();

?>
