<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Add Assignment");
$site->setPage($page);
$teacher = new teacher();

if (!$teacher->is_logged() ) {
    header("location: /error.php?errorID=19");
    exit();
}

if (!isset($_POST["description"])) {

    /*	TODO This is the confirm page, operation result should be:
                0 if unsuccessful
                1 if successful
                any other number won't be accepted (see default option)*/
    if (isset($_GET['operation_result'])) {
        $content = "";
        switch ($_GET['operation_result']) {
            case 1:
                $content .= ' 
								<div class="alert alert-success" role="alert">
								    Assignment successfully registered. <a href="addAssignment.php" class="alert-link">Add another Assignment</a> or <a href="index.php" class="alert-link">back to your homepage.</a>
								</div>
							';
                break;
            case 0:
                $content .= '
								<div class="alert alert-danger" role="alert">
								    Error in registering a new assignment. <a href="addAssignment.php" class="alert-link">Retry </a> or <a href="index.php" class="alert-link">back to your homepage.</a>
								</div>
							';

                break;
            default:
                $content .= '
								<div class="alert alert-dark" role="alert">
								    Operation not allowed.
								</div>
							';
        }
    } else {
        $topics = $teacher->get_topics();
        foreach ($topics as $topic) {
            $topic_list .= "<option value='{$topic['TopicID']}'>" . $topic['TopicName'] . "</option>";
        }

        $classes = $teacher->get_assigned_classes_names();
        foreach ($classes as $class) {
            $class_str = $class['YearClass'] . $class['Section'];
            $class_list .= "<option value='{$class['ClassID']}'>" . $class_str . "</option>";
        }

        $content = '<div class="form-group">
					<form method="POST">
						<div class="form-group">
						<label for="Title">Title of the Assignment</label>
						<input type="text" class="form-control" name="title" id="title" placeholder="Exercises on LaGrangean Relaxation">
						</div>
						<div class="form-group">
						<label for="exampleFormControlSelect2">Subject select</label>
						<select class="custom-select" name="topicID" id="topicID">
							' . $topic_list . '
						</select>
						</div>
				
						<div class="form-group">
						<label for="exampleFormControlSelect3">Class select</label>
						<select class="custom-select" name="classID" id="classID">
							' . $class_list . '
						</select>
						</div>
				
						<div class="form-group">
						<label for="exampleFormControlTextarea1">Description of the Assignment</label>
						<textarea class="form-control" id="description" name="description" rows="3"></textarea>
						</div>
						<div class="form-group">
						<label for="exampleFormControlTextarea1">Date</label>
						<input type="date" id="date" name="date">
						</div>
						<button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Confirm</button>	
						
					</form>
					</div>';
    }
} else {

    if ($teacher->insert_new_assignment($_POST["description"], $_POST["topicID"], $_POST["date"], $_POST["classID"])) {
        header("Location: addAssignment.php?operation_result=1");
        die();
    }
    header("Location: addAssignment.php?operation_result=0");
    die();
}

$page->setContent($content);
$site->render();

?>
