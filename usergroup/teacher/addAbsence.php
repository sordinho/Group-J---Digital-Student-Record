<?php
require_once("../../config.php");

$teacher = new teacher();

$site = new csite();
initialize_site($site);
$page = new cpage("Teacher");
$site->setPage($page);

if (!$teacher->is_logged()) {
	header("location: /error.php?errorID=19");
	exit();
}

// If this page was called after performing an operation, the result is shown to the user
if (isset($_GET['operation_result'])) {
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
                    A pasta ca sassa error<a href="addAbsence.php" class="alert-link">Retry </a> or <a href="../teacher/index.php" class="alert-link">back to your homepage.</a>
                </div>';
            break;
        default:
            $content .= '
                <div class="alert alert-dark" role="alert">
                    Operation not allowed.
                </div>';
    }

} else {
    $classes = $teacher->get_assigned_classes();
    $drop_down = "";
    for ($i = 0; $i < sizeof($classes); $i++) {
        $classID = $classes[$i]['ClassID'];
        $yearSection = $classes[$i]['YearClass'] . " " . $classes[$i]['Section'];
        $drop_down .= '<a class="dropdown-item" href="addAbsence.php?classID='.$classID.'">'.$yearSection.'</a>';
    }

    if (!isset($_GET['classID']) && empty($_POST)) {
        $content = '
                <div class="card text-center">
                    <div class="card-header">
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
        //$subject_info = $teacher->get_topics($classID);// let's assume there is no need to assosiace subject to absences
        $select_content = "";

        $table_content = '<div class="card-body">
                            <form method="post" class="form-inline" style="color:#757575" action="addAbsence.php">
                            
                            <table class="table table-striped">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Insert Absence</th>
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
                                        <label class="form-check-label" id="absence_label_$id" for="exampleCheck1">Not Present</label>
                                    </td>
                            </tr>
OUT;
        }
        // Add date field
        $table_content .= '<tr><th><div class="col-xs-12">
                                <label for="date">Date</label>
                                <input type="date" id="date" name="date">
                            </div></th>
                            <th>
                            <small>Doublecheck the date to avoid any mistakes.<br>You, as teacher, are responsible for any incorrect information.</small>
                            </tr>';

        $table_content .= '</tbody>
                        </table>
                        <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Submit</button>
                    </form>
                </div></div>';
        $content = '
                <div class="card text-center">
                    <div class="card-header">
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
        for ($i = 0; $i < sizeof($students_info); $i++) {
            $id = $students_info[$i]['ID'];
            if (isset($_POST["absence_$id"])) {
                $absent = $_POST["absence_$id"] == 'yes';
                //$now = date("Y-m-d H:i:s");
                $date = $date? $date : date("Y-m-d H:i:s");// If no data was set, set it as of now

                if ($absent) {
                    $res = $teacher->insert_absence($id, $date);
                    if (!$res) {
                        header("Location: addAbsence.php?operation_result=0");
                        exit();
                    }
                }
            }
        }
        header("Location: addAbsence.php?operation_result=1");
        exit();
    }
}
$page->setContent($content);
$site->render();
