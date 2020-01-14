<?php
require_once("../../config.php");

$teacher = new teacher();

$site = new csite();
initialize_site($site);
$page = new cpage("Student Presence Verification");
$site->setPage($page);

if (!$teacher->is_logged()) {
	$teacher->get_error(19);
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
        default:
            $content .= '
                <div class="alert alert-dark" role="alert">
                    Operation not allowed.
                </div>';
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
        $drop_down .= '<a class="dropdown-item" href="addAbsence.php?classID='.$classID.'&date='.$today.'">'.$yearSection.'</a>';
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
        $_SESSION['classID'] = $_GET['classID'];
        $students_info = $teacher->get_students_by_class_id($_GET['classID']);
        // let's assume there is no need to associate subject to absences

        $select_content = "";
        $classID =  $_GET['classID'];
        if(isset($_GET['date']))
            $today = $_GET['date'];
        else
            $today = date("Y-m-d");
        $absences_info = $teacher->get_daily_absences($today,$classID);
        for ($i = 0; $i < sizeof($classes); $i++) {
            $yearSection = $classes[$i]['YearClass'] . " " . $classes[$i]['Section'];
            if ($classes[$i]['ClassID'] == $classID){
                break;
            }
        }
        $id_status = array();
        $exitHours = array();
        for ($i = 0; $i < sizeof($absences_info); $i++) {
            $late = $absences_info[$i]['Late'];
            $exitHour = $absences_info[$i]['ExitHour'];
            if ($exitHour == 0)
                $id_status[$absences_info[$i]['StudentID']] = "Absent";
            else if ($late == 1 and $exitHour == 6)
                $id_status[$absences_info[$i]['StudentID']] = "Late";
            else if ($late == 0 and $exitHour < 6) {
                $id_status[$absences_info[$i]['StudentID']] = "EarlyExit";
                $exitHours[$absences_info[$i]['StudentID']] = $exitHour;
            } else if ($late == 1 and $exitHour < 6) {
                $id_status[$absences_info[$i]['StudentID']] = "LateAndEarlyExit";
                $exitHours[$absences_info[$i]['StudentID']] = $exitHour;
            }
        }
        $table_content = '<div class="card-body">
                            <form method="post" class="form-inline" style="color:#757575" action="addAbsence.php">
                            <div class="table-responsive">
                            <table class="table table-striped">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Absence</th>
                                <th scope="col">Late Arrival</th>
                                <th scope="col">Exit Hour</th>
                                </tr>
                            </thead>
                            <tbody>';

        for ($i = 0; $i < sizeof($students_info); $i++) {
            $name = $students_info[$i]['Name'];
            $surname = $students_info[$i]['Surname'];
            $id = $students_info[$i]['ID'];
            $stud_num =$i+1;
            $status = $id_status[$id];
            $inputAbsent = "<input type=\"checkbox\" class=\"form-check-input\" id=\"absence_$id\" name=\"absence_$id\" value=\"yes\">";
            $inputLate = "<input type=\"checkbox\" class=\"form-check-input\" id=\"late_$id\" name=\"late_$id\" value=\"yes\" >";
            $inputEarlyExit = "<select class=\"form-control text-center\" name=\"early_exit_hour_$id\">
                                              <option value=\"6\">14</option>
                                              <option value=\"5\">13</option>
                                              <option value=\"4\">12</option>
                                              <option value=\"3\">11</option>
                                              <option value=\"2\">10</option>
                                              <option value=\"1\">9</option>
                                            </select>";
            if(!$status)
                $status="none";
            else if($status == "Absent")
                $inputAbsent ="<input type=\"checkbox\" class=\"form-check-input\" id=\"absence_$id\" name=\"absence_$id\" value=\"yes\" checked>";
            else if($status == "Late")
                $inputLate = "<input type=\"checkbox\" class=\"form-check-input\" id=\"late_$id\" name=\"late_$id\" value=\"yes\" checked>";
            else if($status == "EarlyExit") {
                $inputEarlyExit = "<select class=\"form-control text-center\" name=\"early_exit_hour_$id\">
                                              <option value=\"6\">14</option>";
                for($j = 0; $j < 5; $j++){
                    $selected = "";
                    if(($exitHours[$id] - ($j+1)) == 0){
                        $selected= "selected";
                    }
                    $inputEarlyExit.= "<option value=\"".($j+1)."\" $selected>".(8+$j+1)."</option>";
                }
                $inputEarlyExit.="</select>";
            }
            else if($status == "LateAndEarlyExit"){
                $inputLate = "<input type=\"checkbox\" class=\"form-check-input\" id=\"late_$id\" name=\"late_$id\" value=\"yes\" checked>";
                $inputEarlyExit = "<select class=\"form-control text-center\" name=\"early_exit_hour_$id\">
                                              <option value=\"6\">14</option>";
                for($j = 0; $j < 5; $j++){
                    $selected = "";
                    if(($exitHours[$id] - ($j+1)) == 0){
                        $selected= "selected";
                    }
                    $inputEarlyExit.= "<option value=\"".($j+1)."\" $selected>".(8+$j+1)."</option>";
                }
                $inputEarlyExit.="</select>";
            }
            $table_content .= <<<OUT
                            <tr>
                                <th scope="row">$stud_num</th>
                                    <td><div class="col-xs-2 m-2">$surname</div></td>
                                    <td><div class="col-xs-2 m-2">$name</div></td>
                                    <td>
                                        $inputAbsent
                                        <label class="form-check-label" id="absence_label_$id" for="exampleCheck1">Absent</label>
                                        <input type="text" class="form-check-input" id="status_$id" name="status_$id" value="$status" hidden>
                                    </td>
                                    <td>
                                        $inputLate
                                        <label class="form-check-label" id="late_label_$id" for="exampleCheck1">Late</label>
                                    </td>
                                    <td>
                                        <div class="form-group justify-content-center">
                                            $inputEarlyExit
                                        </div>
                                    </td>
                            </tr>
OUT;
        }
        // Add date field
        $table_content .= '
                                    
                                    </tbody>
                                    </table>
                                    </div>';

        $table_content .= '
                            <div class="col-sm-12">
                                        <label for="date">Date</label>
                                        <input type="date" id="date" class="form-control" name="date" onchange="changedDate(this)" value="'.$today.'">
                            </div>
                            <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Submit</button>
                        </form>
                        </div>
                        </div>';
        $classID=$_GET['classID'];
        $content = '
                <div class="card text-center">
                <script type="text/javascript"><!--
                function changedDate(elem){
                    let date = elem.value;
                    let id = window.location.search.substr(1).split("&")[0].split("=")[1];
                    
                    window.location.replace("addAbsence.php?classID="+id+"&date="+date);
                }
                --></script>
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
        $classID = $_SESSION['classID'];
        $students_info = $teacher->get_students_by_class_id($classID);
        if (sizeof($students_info) == 0) {
            header("Location: addAbsence.php?operation_result=-1");
            exit();
        }
        $counter = 0;
        $flag = false;
        //$string = "aId:".isset($_POST["absence_2"])."---lID:".isset($_POST["late_2"])."---eeID:".isset($_POST["early_exit_hour_2"]);
        //header("Location: addAbsence.php?operation_result=$string");
        //exit();
        //TD
        for ($i = 0; $i < sizeof($students_info); $i++) {
            //*******************************************************
            $id = $students_info[$i]['ID'];
            $absent = false;
            $late = false;
            $earlyExit = false;
            $statusA = false;
            $statusL = false;
            $flag = false;
            if (isset($_POST["absence_$id"])&&isset($_POST["status_$id"])) {
                $absent = $_POST["absence_$id"] == 'yes';
                $statusA = $_POST["status_$id"] != 'Absent';
                $statusL = $_POST["status_$id"] != 'Late';
                $flag=true;
            }


            //*******************************************************

            if (isset($_POST["late_$id"])&&isset($_POST["status_$id"])) {
                $late = $_POST["late_$id"] == 'yes';
                if(!$flag) {
                    $statusA = $_POST["status_$id"] != 'Absent';
                    $statusL = $_POST["status_$id"] != 'Late';
                }
                $flag=true;
            }
            //***************************************************

            if(($_POST["early_exit_hour_$id"]!=6)&&isset($_POST["status_$id"])){
                $earlyExit = $_POST["early_exit_hour_$id"] != 6;
                if(!$flag) {
                    $statusA = $_POST["status_$id"] != 'Absent';
                    $statusL = $_POST["status_$id"] != 'Late';
                }
                $flag=true;
            }
            if($flag){
                $date = $_POST['date'];
                if(!$date){
                    $date = date("Y-m-d H:i:s");
                } else {
                    $newD = date_create($date);
                    date_time_set($newD,00,00,00);
                    $date= date_format($newD,"Y-m-d H:i:s");
                }
                if($absent and $late and $earlyExit){ //sei stato segnato come assente
                    if($statusA and $statusL){ // non eri già assente ne in ritardo
                        //todo registra il ritardo + uscita anticipata
                        $res = $teacher->register_early_exit($id,$date,$_POST["early_exit_hour_$id"],1);
                        if (!$res) {
                            $teacher->get_error(22);
                            exit();
                        }
                        $counter++;
                    } else if($statusA and !$statusL) { //eri in ritardo
                        //todo registra uscita anticipata
                        $res = $teacher->register_early_exit($id,$date,$_POST["early_exit_hour_$id"]);
                        if (!$res) {
                            $teacher->get_error(22);
                            exit();
                        }
                        $counter++;
                    } else if($statusL and !$statusA){ //eri assente
                        //todo registra il ritardo + uscita anticipata
                        $res = $teacher->register_early_exit($id,$date,$_POST["early_exit_hour_$id"],1);
                        if (!$res) {
                            $teacher->get_error(22);
                            exit();
                        }
                        $counter++;
                    }
                } else if ($absent and $late and !$earlyExit){
                    if($statusA and $statusL){ // non eri già assente ne in ritardo
                        //todo registra il ritardo
                        $res = $teacher->register_late_arrival($id,$date);
                        if (!$res) {
                            $teacher->get_error(22);
                            exit();
                        }
                        $counter++;
                    } else if($statusA and !$statusL) { //eri in ritardo
                        //todo non fare nulla
                        //$counter++;
                    } else if($statusL and !$statusA){ //eri assente
                        //todo registra il ritardo
                        $res = $teacher->register_late_arrival($id,$date);
                        if (!$res) {
                            $teacher->get_error(22);
                            exit();
                        }
                        $counter++;
                    }
                } else if( $absent and !$late and $earlyExit){
                    if($statusA and $statusL){ // non eri già assente ne in ritardo
                        //todo registra assenza
                        $res = $teacher->register_absence($id,$date);
                        if (!$res) {
                            $teacher->get_error(22);
                            exit();
                        }
                        $counter++;
                    } else if($statusA and !$statusL) { //eri in ritardo
                        //todo registra assenza
                        $res = $teacher->register_absence($id,$date);
                        if (!$res) {
                            $teacher->get_error(22);
                            exit();
                        }
                        $counter++;
                    } else if($statusL and !$statusA){ //eri assente
                        //todo non fare nulla
                        //$counter++;
                    }
                } else if( $absent and !$late and !$earlyExit){
                    if($statusA and $statusL){ // non eri già assente ne in ritardo
                        //todo registra assenza
                        $res = $teacher->register_absence($id,$date);
                        if (!$res) {
                            $teacher->get_error(22);
                            exit();
                        }
                        $counter++;
                    } else if($statusA and !$statusL) { //eri in ritardo
                        //todo registra assenza
                        $res = $teacher->register_absence($id,$date);
                        if (!$res) {
                            $teacher->get_error(22);
                            exit();
                        }
                        $counter++;
                    } else if($statusL and !$statusA){ //eri assente
                        //todo non fare nulla
                        //$counter++;
                    }
                } else if(!$absent and $late and $earlyExit){
                    if($statusA and $statusL){ // non eri già assente ne in ritardo
                        //todo registra il ritardo + ee
                        $res = $teacher->register_early_exit($id,$date,$_POST["early_exit_hour_$id"],1);
                        if (!$res) {
                            $teacher->get_error(22);
                            exit();
                        }
                        $counter++;
                    } else if($statusA and !$statusL) { //eri in ritardo
                        //todo registra ee
                        $res = $teacher->register_early_exit($id,$date,$_POST["early_exit_hour_$id"]);
                        if (!$res) {
                            $teacher->get_error(22);
                            exit();
                        }
                        $counter++;
                    } else if($statusL and !$statusA){ //eri assente
                        //todo registra il ritardo  + ee
                        $res = $teacher->register_early_exit($id,$date,$_POST["early_exit_hour_$id"],1);
                        if (!$res) {
                            $teacher->get_error(22);
                            exit();
                        }
                        $counter++;
                    }
                } else if(!$absent and $late and !$earlyExit){
                    if($statusA and $statusL){ // non eri già assente ne in ritardo
                        //todo registra il ritardo
                        $res = $teacher->register_late_arrival($id,$date);
                        if (!$res) {
                            $teacher->get_error(22);
                            exit();
                        }
                        $counter++;
                    } else if($statusA and !$statusL) { //eri in ritardo
                        //todo non fare nulla
                        //$counter++;
                    } else if($statusL and !$statusA){ //eri assente
                        //todo registra il ritardo
                        $res = $teacher->register_late_arrival($id,$date);
                        if (!$res) {
                            $teacher->get_error(22);
                            exit();
                        }
                        $counter++;
                    }
                } else if(!$absent and !$late and $earlyExit){
                    if($statusA and $statusL){ // non eri già assente ne in ritardo
                        //todo registra ee
                        $res = $teacher->register_early_exit($id,$date,$_POST["early_exit_hour_$id"]);
                        if (!$res) {
                            $teacher->get_error(22);
                            exit();
                        }
                        $counter++;
                    } else if($statusA and !$statusL) { //eri in ritardo
                        //todo registra ee
                        $res = $teacher->register_early_exit($id,$date,$_POST["early_exit_hour_$id"]);
                        if (!$res) {
                            $teacher->get_error(22);
                            exit();
                        }
                        $counter++;
                    } else if($statusL and !$statusA){ //eri assente
                        //todo non fare nulla
                        //$counter++;
                    }
                }
            }
        }
        //********************************************

       /* for ($i = 0; $i < sizeof($students_info); $i++) {
            $id = $students_info[$i]['ID'];
            if (isset($_POST["absence_$id"])&&isset($_POST["status_$id"])) {
                $absent = $_POST["absence_$id"] == 'yes';
                $status = $_POST["status_$id"] != 'Absent';

                //todo in questo momento status permette di evitare di inserire 2 volte una assenza per una stessa persona
                // se si vuole limitare l'inserimento di una assenza solo a chi è in status "none" (nè assente nè in ritardo nè in early exit
                // modificare la riga precedente
                $date = $_POST['date'];
                if(!$date){
                    $date = date("Y-m-d H:i:s");
                } else {
                    $newD = date_create($date);
                    date_time_set($newD,00,00,00);
                    $date= date_format($newD,"Y-m-d H:i:s");
                }

                if ($absent and $status) {
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
                $status = $_POST["status_$id"] != 'Late';
                //todo in questo momento status permette di evitare di inserire 2 volte un ritardo per una stessa persona
                // se si vuole limitare l'inserimento di un ritardo solo a chi è assente o è in status "none" modificare la riga precedente
                $date = $_POST['date'];
                if(!$date){
                    $date = date("Y-m-d H:i:s");
                } else {
                    $newD = date_create($date);
                    date_time_set($newD,00,00,00);
                    $date= date_format($newD,"Y-m-d H:i:s");
                }

                if ($absent and $status) {
                    $res = $teacher->register_late_arrival($id, $date);
                    if (!$res) {
                        header("Location: addAbsence.php?operation_result=0");
                        exit();
                    }
                    $counter++;
                }
            }
        }
        for ($i = 0; $i < sizeof($students_info);$i++){
            $id = $students_info[$i]['ID'];
            if(isset($_POST["early_exit_hour_$id"])&&isset($_POST["status_$id"])){
                $earlyExit = $_POST["early_exit_hour_$id"] != 6;
                $status = $_POST["status_$id"] != 'Absent';
                $date = $_POST['date'];
                if(!$date){
                    $date = date("Y-m-d H:i:s");
                } else {
                    $newD = date_create($date);
                    date_time_set($newD,00,00,00);
                    $date= date_format($newD,"Y-m-d H:i:s");
                }
                if($earlyExit and $status){
                    $res = $teacher->register_early_exit($id,$date,$_POST["early_exit_hour_$id"]);
                    if (!$res) {
                        header("Location: addAbsence.php?operation_result=0");
                        exit();
                    }
                    $counter++;
                }
            }
        }*/
        if ($counter > 0) {
            header("Location: addAbsence.php?operation_result=1");
            exit();
        } else if ($counter == 0){
            $teacher->get_error(21);
            exit();
        } else {
            $teacher->get_error(22);
            exit();
        }
    }
}
$page->setContent($content);
$site->render();
