<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Add lecture");
$site->setPage($page);
$teacher = new teacher($_SESSION);

if(!$teacher ->is_logged() || !$teacher ->get_teacher_ID()){
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

	/*	TODO This is the confirm page, operation result should be:
				0 if unsuccessful
				1 if successful
				any other number won't be accepted (see default option)
	uncomment if it is ok
*/
	if(isset($_GET['operation_result'])){
        $content = "";
        switch ($_GET['operation_result']){
            case 1:
                $content.=<<<OUT
    <div class="alert alert-success" role="alert">
      Lecture successfully registered. <a href="addLecture.php" class="alert-link">Add another lecture</a> or <a href="index.php" class="alert-link">back to your homepage.</a>
    </div>
OUT;
                break;
            case 0:
                $content.=<<<OUT
    <div class="alert alert-danger" role="alert">
     Error in registering a new lecture. <a href="addLecture.php" class="alert-link">Retry </a> or <a href="index.php" class="alert-link">back to your homepage.</a>
    </div>
OUT;

                break;
            default:
                $content.=<<<OUT
    <div class="alert alert-dark" role="alert">
      Operation not allowed.
    </div>
OUT;
        }
    } else {


//$content ='<a href="usergroup/teacher/teacherAction1ToMove.php">Action1To incorporate in MENU</a>';
	$topics = $teacher->get_topics();
	foreach ($topics as $topic) {
		$topic_list .= "<option value='{$topic['TopicID']}'>".$topic['TopicName']."</option>";
	}

	$classes = $teacher->get_assigned_classes();
	foreach ($classes as $class) {//TODO extend with class name (i.e. 4I)
		$class_list.= "<option value='{$class['ClassID']}'>".$class['ClassID']."</option>";
	}

	$content = '<div class="container article-clean">
	<div class="row">
	<form method="POST">
		<div class="form-group">
		<label for="Title">Title of the lecture</label>
		<input type="text" class="form-control" name="title" id="title" placeholder="Course Introduction">
		</div>
		<div class="form-group">
		<label for="exampleFormControlSelect2">Subject select</label>
		<select multiple class="form-control" name="topicID" id="topicID">
			'.$topic_list.'
		</select>
		</div>

		<div class="form-group">
		<label for="exampleFormControlSelect3">Class select</label>
		<select multiple class="form-control" name="classID" id="classID">
			'.$class_list.'
		</select>
		</div>

		<div class="form-group">
		<label for="exampleFormControlTextarea1">Description of the lecture</label>
		<textarea class="form-control" id="description" name="description" rows="3"></textarea>
		</div>
		<div class="form-group">
		<label for="exampleFormControlTextarea1">Date</label>
		<input type="date" id="date" name="date">
		</div>
		<button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Record</button>	
		
	</form>
	</div>
	</div>';
	}
} else{
	//print("Now we should insert the topic");
	//	public function insert_new_lecture_topic($lectureDescription, $topicID, $timestamp) {

	if($teacher->insert_new_lecture_topic($_POST["description"], $_POST["topicID"], $_POST["date"], $_POST["classID"])){
		header("Location: addLecture.php?operation_result=1");
		die();
	}
	header("Location: addLecture.php?operation_result=0");
	die();
}

$page->setContent($content);
$site->render();

?>
