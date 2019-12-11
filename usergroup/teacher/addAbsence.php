<?php
require_once("../../config.php");

$teacher = new teacher();

$site = new csite();
initialize_site($site);
$page = new cpage("Student Presence Verification");
$site->setPage($page);

if (!$teacher->is_logged()) {
	header("location: /error.php?errorID=19");
	exit();
}

// If this page was called after performing an operation, the result is shown to the user
if (isset($_GET['operation_result'])) {
    /*$print = $_GET['operation_result'];
    $content .= '
                <div class="alert alert-danger" role="alert">'.
                $print.
                '</div>';*/
    switch ($_GET['operation_result']) {
        case 1:
            $content .= '
                <div class="alert alert-success" role="alert">
                    Absences successfully registered. <a href="addAbsence.php" class="alert-link">Keep registering absence</a> or <a href="../teacher/index.php" class="alert-link">back to your homepage.</a>
                </div>';
            break;
        case 0:
            $content .= '
                <div class="alert alert-danger" role="alert">
                    Error in uploading students\' absence. <a href="addAbsence.php" class="alert-link">Retry </a> or <a href="../teacher/index.php" class="alert-link">back to your homepage.</a>
                </div>'; 
            break;
        case -1:
            $content .= '
                <div class="alert alert-danger" role="alert">
                    Error <a href="addAbsence.php" class="alert-link">Retry </a> or <a href="../teacher/index.php" class="alert-link">back to your homepage.</a>
                </div>';
            break;
        case -2:
            $content .= '
                <div class="alert alert-danger" role="alert">
                    No student selected. <a href="addAbsence.php" class="alert-link">Retry </a> or <a href="../teacher/index.php" class="alert-link">back to your homepage.</a>
                </div>';
            break;
        default:
            $content .= '
                <div class="alert alert-dark" role="alert">
                    Operation not allowed.
                </div>';
    }

} else {
    $classes = $teacher->get_assigned_classes_names();
    $drop_down = "";
    for ($i = 0; $i < sizeof($classes); $i++) {
        $classID = $classes[$i]['ClassID'];
        $yearSection = $classes[$i]['YearClass'] . " " . $classes[$i]['Section'];
        $drop_down .= '<a class="dropdown-item" href="addAbsence.php?classID='.$classID.'">'.$yearSection.'</a>';
    }

    if (!isset($_GET['classID']) && empty($_POST)) {
        $content = '
                <div class="card text-center">
                    <div class="card-header" style="background-color:rgba(108,108,108,0.9);color:white">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle btn-lg" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Choose a class
                            </button>
                            <div class="dropdown-menu">'.
                                $drop_down.
                            '</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Select a class to insert a new absence record.</p>
                    </div>
                </div>';

    } else if (isset($_GET['classID'])) {
        $students_info = $teacher->get_students_by_class_id($_GET['classID']);
        // let's assume there is no need to associate subject to absences
        //if(isset())
        //$absences_info = $teacher->get_daily_absences()
        $select_content = "";
        $classID =  $_GET['classID'];
        for ($i = 0; $i < sizeof($classes); $i++) {
            $yearSection = $classes[$i]['YearClass'] . " " . $classes[$i]['Section'];
            if ($classes[$i]['ClassID'] == $classID){
                break;
            }
        }
        $table_content = '<div class="card-body">
                            <form method="post" class="form-inline" style="color:#757575" action="addAbsence.php">
                            <table class="table table-striped">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Absence</th>
                                <th scope="col">Late Arrival</th>
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
                                        <input type="checkbox" class="form-check-input" id="absence_$id" name="absence_$id" value="yes" >
                                        <label class="form-check-label" id="absence_label_$id" for="exampleCheck1">Absent</label>
                                    </td>
                                    <td>
                                        <input type="checkbox" class="form-check-input" id="late_$id" name="late_$id" value="yes" >
                                        <label class="form-check-label" id="late_label_$id" for="exampleCheck1">Late</label>
                                    </td>
                            </tr>
OUT;
        }
        // Add date field
        $table_content .= '
                                    
                                    </tbody>
                                    </table>';

        $table_content .= '
                            <div class="col-sm-12">
                                        <label for="date">Date</label>
                                        <input type="date" id="date" class="form-control" name="date">
                            </div>
                            <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Submit</button>
                        </form>
                        </div>
                        </div>';
        $content = '
                <div class="card text-center">
                    <div class="card-header" style="background-color:rgba(108,108,108,0.9);color:white">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle btn-lg" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            '.$yearSection.'
                        </button>
                        <div class="dropdown-menu">
                            '.$drop_down.'
                        </div>
                        </div>
                    </div>
                <div class="card-body">'.
                $table_content;

    // If a form was just sent, elaborate its data and perform operation on backend
    } else if (!empty($_POST)) {
        $students_info = $teacher->get_students_by_class_id($classID);
        if (sizeof($students_info) == 0) {
            header("Location: addAbsence.php?operation_result=-1");
            exit();
        }
        $counter = 0;
        for ($i = 0; $i < sizeof($students_info); $i++) {
            $id = $students_info[$i]['ID'];
            if (isset($_POST["absence_$id"])) {
                $absent = $_POST["absence_$id"] == 'yes';

                $date = $_POST['date'];
                if(!$date){
                    $date = date("Y-m-d H:i:s");
                } else {
                    $newD = date_create($date);
                    date_time_set($newD,00,00,00);
                    $date= date_format($newD,"Y-m-d H:i:s");
                }
                //$date = $date ? $date : date("Y-m-d H:i:s");// If no data was set, set it as of now

                if ($absent) {
                    $res = $teacher->register_absence($id, $date);
                    if (!$res) {
                        header("Location: addAbsence.php?operation_result=0");
                        exit();
                    }
                    $counter++;
                }
            }
        }

        for ($i = 0; $i < sizeof($students_info); $i++) {
            $id = $students_info[$i]['ID'];
            if (isset($_POST["late_$id"])) {
                $absent = $_POST["late_$id"] == 'yes';

                $date = $_POST['date'];
                if(!$date){
                    $date = date("Y-m-d H:i:s");
                } else {
                    $newD = date_create($date);
                    date_time_set($newD,00,00,00);
                    $date= date_format($newD,"Y-m-d H:i:s");
                }
                //$date = $date ? $date : date("Y-m-d H:i:s");// If no data was set, set it as of now

                if ($absent) {
                    $res = $teacher->register_late_arrival($id, $date);
                    if (!$res) {
                        header("Location: addAbsence.php?operation_result=0");
                        exit();
                    }
                    $counter++;
                }
            }
        }
        if ($counter > 0) {
            header("Location: addAbsence.php?operation_result=1");
            exit();
        } else if ($counter == 0){
            header("Location: addAbsence.php?operation_result=-2");
            exit();
        } else {
            header("Location: addAbsence.php?operation_result=0");
            exit();
        }
    }
}
$page->setContent($content);
$site->render();
