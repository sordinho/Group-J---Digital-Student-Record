<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("");
$site->setPage($page);

$teacher = new teacher();

if (!$teacher->is_logged()) {
    header("location: /error.php?errorID=19");
    exit();
}

//if a class has been selected loads the list of students of the class so that the officer can modify it
if (isset($_GET['classID'])) {
    $content .= '<ul class="list-group">';

    $students = $teacher->get_students_by_class_ID($_GET['classID']);
    $_SESSION["classID"] = $_GET['classID'];
    foreach ($students as $student){
        if(!$teacher->has_final_grades($student['ID'],$teacher->get_actual_term(),$_GET['classID']))
            $enter=true;
    }
    if (!$enter) {
        $content = '<div class="alert alert-danger" role="alert">
                            There are no students in the selected Class.<a href="'.PLATFORM_PATH.'/usergroup/teacher/index.php" alert-link">Back to homepage.</a>
                        </div>
                    ';
    } else {
        $content .=

            '<script type="text/javascript">
            <!--
            function selectStudent(elem){
                window.location.replace("assignFinalGrades.php?studentID="+elem);
            }
            -->
        </script>';
        $content .= '
            <div class="card">
                <h2 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">
                    Students List of Class: '.$teacher->get_class_stamp_by_id($_SESSION["classID"]).'
                </h2>
                <div class="card-body  px-lg-5 pt-0 mt-md-5">
                    <form>
                    <table class="table table-striped">
                        <thead >
                        <tr>
                            <th scope="col">LastName</th>
                            <th scope="col">Name</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        ';

        foreach ($students as $student) {
            if(!($teacher->has_final_grades($student['ID'],$teacher->get_actual_term(),$_GET['classID'])))
                $content .= "
                            <tr>
                                <td>" . $student['Surname'] . "</td>
                                <td>" . $student['Name'] . "</td>
                                <td><button class='btn btn-primary' value='SelezionaStudente' id=" . $student['ID'] . " type='button' onclick='selectStudent(id)'> Select </button></td>
                            </tr>";
        }
        $content .= "
                        </tbody>
                    </table> </form>
                </div>
            </div>";

    }


    $content .= "
    </ul>
";

} else if (isset($_GET['studentID'])) {
    $content .= '<ul class="list-group">';

    $marks = $teacher -> get_missing_term_marks($_GET['studentID'],$teacher->get_actual_term(),$_SESSION['classID']);
    $_SESSION["classID"] = intval($_GET['classID']);
    if (count($marks) == 0) {
        $content = '<div class="alert alert-danger" role="alert">
                            There are no final terms to assign to selected student.<a href=".PLATFORM_PATH."/usergroup/teacher/index.php" class="alert-link">Back to home page.</a>
                        </div>
                    ';
    } else {
        $content .=

            '<script type="text/javascript">
            <!--
            function setFinalTerm(elem){
                
                window.location.replace("assignFinalGrades.php?setFinalTerm="+elem);
            }
            -->
        </script>';
        $content .= '
            <div class="card">
                <h2 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">
                    Final Term Marks of:  '.$teacher->get_student_stamp_by_id($_GET['studentID']).'
                </h2>
                <div class="card-body  px-lg-5 pt-0 mt-md-5">
                    <form>
                    <table class="table table-striped">
                        <thead >
                        <tr>
                            <th scope="col">Topic</th>
                            <th scope="col">Average</th>
                            <th scope="col">Decision</th>
                        </tr>
                        </thead>
                        <tbody>
                        ';

        foreach ($marks as $mark) {
            $average=$teacher->get_average_mark_for_topic($mark['TopicID'],$_GET['studentID']);
            if(! preg_match('/^\d+$/', round($average,2))){
                $averagePlus=$average+0.5;
                $averageMinus=$average-0.5;
            }else{
                $averagePlus=$average;
                $averageMinus=$average;
            }
            $content .= "
                            <tr>
                                <td>" . $mark['Name'] . "</td>
                                <td>" . round($average,2) . "</td>
                                <td><button class='btn btn-primary' value='Up' id=" . $_GET['studentID']."_".$mark['TopicID']."_".round($averageMinus,0)."_".$teacher->get_actual_term()." type='button' onclick='setFinalTerm(id)'>". round($averageMinus,0) ." </button>
                                <button class='btn btn-primary' value='Up' id=" . $_GET['studentID']."_".$mark['TopicID']."_".round($averagePlus,0)."_".$teacher->get_actual_term()." type='button' onclick='setFinalTerm(id)'>". round($averagePlus,0) ."</button></td>
                            </tr>";
        }
        $content .= "
                        </tbody>
                    </table> </form>
                </div>
            </div>";

    }


    $content .= "
    </ul>";
} else if (isset($_GET['setFinalTerm'])) {
    $classID=$teacher->set_final_grade($_GET['setFinalTerm']);
    header("refresh:0.01;url=assignFinalGrades.php?classID=". $classID);
} else {
    header("location: /error.php?errorID=20");
    exit();
}


$page->setContent($content);
$site->render();