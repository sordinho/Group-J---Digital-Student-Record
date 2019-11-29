<?php
require_once("../../config.php");

$teacher = new teacher();

$site = new csite();
initialize_site($site);
$page = new cpage("Teacher");
$site->setPage($page);

if (!$teacher->is_logged() ) {
	header("location: /error.php?errorID=19");
	exit();
}

if (isset($_GET['operation_result'])) {
    switch ($_GET['operation_result']) {
        case 1:
            $content .= <<<OUT
<div class="alert alert-success" role="alert">
  Grades successfully registered. <a href="insertGrades.php" class="alert-link">Keep registering grades</a> or <a href="../teacher/index.php" class="alert-link">back to your homepage.</a>
</div>
OUT;
            break;
        case 0:
            $content .= <<<OUT
<div class="alert alert-danger" role="alert">
 Error in uploading students' grades. <a href="insertGrades.php" class="alert-link">Retry </a> or <a href="../teacher/index.php" class="alert-link">back to your homepage.</a>
</div>
OUT;
            break;
        default:
            $content .= <<<OUT
<div class="alert alert-dark" role="alert">
  Operation not allowed.
</div>
OUT;
    }


} else {

    $classes = $teacher->get_assigned_classes();
    $drop_down = "";
    for ($i = 0; $i < sizeof($classes); $i++) {
        $classID = $classes[$i]['ClassID'];
        $yearSection = $classes[$i]['YearClass'] . " " . $classes[$i]['Section'];
        $drop_down .= <<<OUT
                    <a class="dropdown-item" href="insertGrades.php?classID=$classID">$yearSection</a>
OUT;
    }
    if (!isset($_GET['classID']) && empty($_POST)) {

        $content = <<<OUT
                <div class="card text-center">
                  <div class="card-header">
                    <div class="btn-group">
                  <button type="button" class="btn btn-primary dropdown-toggle btn-lg" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Choose a class
                  </button>
                  <div class="dropdown-menu">
                    $drop_down
                  </div>
                </div>
                  </div>
                  <div class="card-body">
                    <p class="card-text">Select a class to insert a new grade.</p>
                  </div>
                </div>
                
OUT;
    } else if (isset($_GET['classID'])) {
        $students_info = $teacher->get_students_by_class_id($_GET['classID']);
        //$classID = $_GET['classID'];
        $subject_info = $teacher->get_topics($classID);
        $select_content = "";

        for ($i = 0; $i < sizeof($subject_info); $i++) {
            $subjectID = $subject_info[$i]['TopicID'];
            $subjectName = $subject_info[$i]['TopicName'];
            $select_content .= "<option value='$subjectID'>$subjectName</option>";
        }


        $table_content = '<script type="text/javascript"><!--
function enableLaude(elem){
    let id = elem.getAttribute("id");
    let grade = parseFloat(elem.value);
    if(grade == 10)
        document.getElementById("laude_"+id).disabled=false;
    else if(grade != 10 && document.getElementById("laude_"+id).disabled==false){
        document.getElementById("laude_"+id).disabled=true;
        document.getElementById("lause_"+id).checked = false;    
    }
}
--></script>
                      <div class="card-body">
                      <form method="post" class="form-inline" style="color:#757575" action="insertGrades.php">
                        <table class="table table-striped">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Last Name</th>
                          <th scope="col">First Name</th>
                          <th scope="col">Insert Grade</th>
                        </tr>
                      </thead>
                      <tbody>';

        for ($i = 0; $i < sizeof($students_info); $i++) {
            $name = $students_info[$i]['Name'];
            $surname = $students_info[$i]['Surname'];
            $id = $students_info[$i]['ID'];
            $stud_num =$i+1;
            $table_content .= <<<OUT
                            <tr>
                                <th scope="row">$stud_num</th>
                                    <td><div class="col-xs-2 m-2">$surname</div></td>
                                    <td><div class="col-xs-2 m-2">$name</div></td>
                                    <td>
                                        <div class="form-group row">
                                            <div class="col-xs-2 pl-2 pr-2">
                                                <input type="number" onchange="enableLaude(this)" id="$id" placeholder="grade" name="grade_$id" class="form-control" step="0.25" min="0" max="10">
                                            </div>
                                            <div class="col-xs-2 pl-2 pr-2">
                                                <select class='class="browser-default custom-select custom-select-lg"' name='subjectID_$id'>
                                                    <option value="" disabled selected>Choose a subject</option>
                                                        $select_content;
                                                </select>
                                            </div>
                                                      
                                            <div class="col-xs-2 pl-2 pr-2">
                                                <input type="checkbox" class="form-check-input" id="laude_$id" name="laude_$id" value="yes" disabled>
                                                <label class="form-check-label" id="laude_label_$id" for="exampleCheck1">Laude</label>
                                            </div>
                                        </div>
                                    </td>
                            </tr>
OUT;
        }

        $table_content .= '</tbody>
                        </table>
                        <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Submit</button>
                        </form>
                          </div>
                        </div>';
        $content = <<<OUT
                <div class="card text-center">
                  <div class="card-header">
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary dropdown-toggle btn-lg" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        $yearSection
                      </button>
                      <div class="dropdown-menu">
                        $drop_down
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                  $table_content
OUT;
    } else if (!empty($_POST)) {
        $students_info = $teacher->get_students_by_class_id($classID);
        $counter = 0;
        if (sizeof($students_info) == 0) {
            header("Location: insertGrades.php?operation_result=-1");
            die();
        }
        for ($i = 0; $i < sizeof($students_info); $i++) {
            $id = $students_info[$i]['ID'];
            if (isset($_POST["subjectID_$id"]) && isset($_POST["grade_$id"])) {
                $counter++;
                $now = date("Y-m-d H:i:s");
                $laude = false;
                $subID = (int)$_POST["subjectID_$id"];
                $grade = (int)$_POST["grade_$id"];
                if (isset($_POST["laude_$id"])) {
                    if ($_POST["laude_$id"] == 'yes')
                        $laude = true;
                }
                if (isset($_POST["grade_$id"])) {
                    $res = $teacher->insert_grade($id, $classID, $subID, $grade, $laude, $now);
                    if (!$res) {
                        header("Location: insertGrades.php?operation_result=0");
                        die();
                    }
                }
            }
        }
        if($counter>0) {
            header("Location: insertGrades.php?operation_result=1");
            die();
        }
        header("Location: insertGrades.php?operation_result=0");
        die();
    }
}
$page->setContent($content);
$site->render();
