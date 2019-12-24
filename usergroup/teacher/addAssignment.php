<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("");
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

        $topics = $teacher->get_assigned_classes();
        foreach ($topics as $topic) {
            $index = array_search('TopicDescription', $topic);
            unset($topic[$index]);
            $value = json_encode($topic);
            $topic_list .= "<option value='$value'>" . $topic['TopicName'] . " - ".$topic['YearClass'].$topic['Section']."</option>";
        }

        /*$topics = $teacher->get_topics();
        foreach ($topics as $topic) {
            $topic_list .= "<option value='{$topic['TopicID']}'>" . $topic['TopicName'] . "</option>";
        }

        $classes = $teacher->get_assigned_classes_names();
        foreach ($classes as $class) {
            $class_str = $class['YearClass'] . $class['Section'];
            $class_list .= "<option value='{$class['ClassID']}'>" . $class_str . "</option>";
        }*/


        $content = '
<div class="card">
					<h2 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">
						Add Assignment
					</h2>
					<div class="card-body  px-lg-5 pt-0 mt-md-5">
					<form method="POST">
						<div class="form-group">
						<label for="exampleFormControlSelect2">Subject select</label>
						<select class="custom-select" name="topic" id="topicID">
							' . $topic_list . '
						</select>
						</div>
				
				        <!--
						<div class="form-group">
						<label for="exampleFormControlSelect3">Class select</label>
						<select class="custom-select" name="classID" id="classID">
							
						</select>
						</div>
				        -->
				
						<div class="form-group">
						<label for="exampleFormControlTextarea1">Description of the Assignment</label>
						<textarea class="form-control" id="description" name="description" rows="3"></textarea>
						</div>
						<div class="form-group">
						<label for="exampleFormControlTextarea1">Deadline</label>
						<input type="date" id="date" name="date" class="form-control">
						</div>
						<button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Confirm</button>	
						
					</form>
					</div>
					</div>
					';
    }
} else {

    $post = json_decode($_POST['topic'], TRUE);
    $topicID = $post['TopicID'];
    $classID = $post['ClassID'];

    if ($teacher->insert_new_assignment($_POST["description"], $topicID, $_POST["date"], $classID)) {
        header("Location: addAssignment.php?operation_result=1");
        die();
    }
    header("Location: addAssignment.php?operation_result=0");
    die();
}

$page->setContent($content);
$site->render();

?>
