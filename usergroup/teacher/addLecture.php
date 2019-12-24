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

    if (isset($_GET['operation_result'])) {
        $content = "";
        switch ($_GET['operation_result']) {
            case 1:
                $content .= '
								<div class="alert alert-success" role="alert">
									Lecture successfully registered. <a href="addLecture.php" class="alert-link">Add another lecture</a> or <a href="index.php" class="alert-link">back to your homepage.</a>
								</div>
							';
                break;
            case 0:
                $content .= '
								<div class="alert alert-danger" role="alert">
									Error in registering a new lecture. <a href="addLecture.php" class="alert-link">Retry </a> or <a href="index.php" class="alert-link">back to your homepage.</a>
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
//$content ='<a href="usergroup/teacher/teacherAction1ToMove.php">Action1To incorporate in MENU</a>';

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
                        <h2 style="background-color:rgba(108,108,108,0.9);color:white" class="card-header info-color white-text text-center py-4">
                            Add lecture
                        </h2>
                        
                        <div class="card-body px-lg-5 pt-0 mt-md-5">
                            <div class="form-group">
                                <form method="POST">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect2">Subject select</label>
                                        <select class="form-control" name="topic" id="topicID">
                                            ' . $topic_list . '
                                        </select>
                                    </div>
                            
                                    <!--
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect3">Class select</label>
                                        <select  class="form-control" name="classID" id="classID">
                                            
                                        </select>
                                    </div>
                                    -->
                            
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Description of the lecture</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Date</label>
                                        <input type="date" id="date" name="date" class="form-control">
                                    </div>
                                    <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Record</button>	
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                            ';
    }
} else {
    //print("Now we should insert the topic");
    //	public function insert_new_lecture_topic($lectureDescription, $topicID, $timestamp) {

    $post = json_decode($_POST['topic'], TRUE);
    $topicID = $post['TopicID'];
    $classID = $post['ClassID'];

    if ($teacher->insert_new_lecture_topic($_POST["description"], $topicID, $_POST["date"], $classID)) {
        header("Location: addLecture.php?operation_result=1");
        die();
    }
    header("Location: addLecture.php?operation_result=0");
    die();
}

$page->setContent($content);
$site->render();

?>
