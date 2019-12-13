<?php
require_once("../../config.php");

$teacher = new teacher();

$site = new csite();
initialize_site($site);
$page = new cpage("Disciplinary Note Registration");
$site->setPage($page);

if (!$teacher->is_logged()) {
    $teacher->get_error(14);
}
if (isset($_GET['operation_result'])) {
    if ($_GET['operation_result'] == 1){
        $content .= '
                <div class="alert alert-success" role="alert">
                    Absences successfully registered. <a href="registerNote.php" class="alert-link">Keep registering absence</a> or <a href="../teacher/index.php" class="alert-link">back to your homepage.</a>
                </div>';
    } else {
        $teacher->get_error(25);
    }

} else {
    $classes = $teacher->get_assigned_classes_names();
    $drop_down = "";
    if(isset($_GET['date']))
        $today = $_GET['date'];
    else
        $today = date("Y-m-d");
    for ($i = 0; $i < sizeof($classes); $i++) {
        $classID = $classes[$i]['ClassID'];
        $yearSection = $classes[$i]['YearClass'] . " " . $classes[$i]['Section'];
        $drop_down .= '<a class="dropdown-item" href="registerNote.php?classID='.$classID.'">'.$yearSection.'</a>';
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
                        <p class="card-text">Select a class to register a new note.</p>
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
                            <form method="post" class="form-inline" style="color:#757575" action="registerNote.php">
                            <table class="table table-striped">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Select</th>
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
                                        <input type="checkbox" class="form-check-input" id="select_$id" name="select_$id" value="yes" >
                                        <label class="form-check-label" id="select_label_$id" for="exampleCheck1">Choose</label>
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
                                        <input type="date" id="date" class="form-control" name="date" value="'.$today.'">
                            </div>
                                <div class="col-lg-12">
                                    <label for="note"><strong>Disciplinary note</strong></label>
                                    <div class="input-group input-group-lg">
                                        <textarea class="form-control" name="note" id="note" rows="3"></textarea>
                                    </div>
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
            $teacher->get_error(24);
            //header("Location: registerNote.php?operation_result=-2");
            //exit();
        }
        $counter = 0;
        $note = $_POST['note'];
        if(!$note){
            $teacher->get_error(23);
            //header("Location: registerNote.php?operation_result=-1");
            //exit();
        }
        $date = $_POST['date'];
        if(!$date){
            $date = date("Y-m-d H:i:s");
        } else {
            $newD = date_create($date);
            date_time_set($newD,00,00,00);
            $date= date_format($newD,"Y-m-d H:i:s");
        }

        $noteId = $teacher->register_new_note($date,$classID,$note);
        if($noteId <= 0){
            $teacher->get_error(22);
            //header("Location: registerNote.php?operation_result=".$noteId);
            //exit();
        }
        for ($i = 0; $i < sizeof($students_info); $i++) {
            $id = $students_info[$i]['ID'];
            if (isset($_POST["select_$id"])) {
                $selected = $_POST["select_$id"] == 'yes';

                if ($selected) {
                    $res = $teacher->register_note_record($id,$noteId);

                    if (!$res) {
                        $teacher->get_error(22);
                    }
                    $counter++;
                }
            }
        }

        if ($counter > 0) {
            header("Location: registerNote.php?operation_result=1");
            exit();
        } else if ($counter == 0){
            $teacher->get_error(21);
            //header("Location: registerNote.php?operation_result=-1");
            //exit();
        } else {
            $teacher->get_error(22);
            //header("Location: registerNote.php?operation_result=0");
            //exit();
        }
    }
}
$page->setContent($content);
$site->render();
