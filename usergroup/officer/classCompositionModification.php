<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("");
$site->setPage($page);

$officer = new officer();

if(!$officer ->is_logged() ){
	header("location: /error.php?errorID=19");
	exit();
}

//if a class has been selected loads the list of students of the class so that the officer can modify it
if (isset($_GET['classID'])) {
    $content .= "<ul class=\"list-group\">";

    $students = $officer->get_students_by_class_ID($_GET['classID']);
    $_SESSION["classID"] = intval($_GET['classID']);
    if (count($students) == 0) {
        $content = "<div class=\"alert alert-danger\" role=\"alert\">
                            There are no students in the selected Class.<a href=".PLATFORM_PATH."/usergroup/officer/classCompositionModification.php class=\"alert-link\">Back to class selection.</a>
                        </div>
                    ";
    } else {
        $content.="
            <div class=\"card\">
                <h2 class=\"card-header info-color white-text text-center py-4\" style=\"background-color:rgba(108,108,108,0.9);color:white\">
                    Students List of Class: ".$officer->get_class_stamp_by_id($_SESSION["classID"])."
                </h2>
                <div class=\"card-body  px-lg-5 pt-0 mt-md-5\">
                ";
        // two js function to add and remove
        $content .=
            "<script type=\"text/javascript\"><!--
            function removeStudent(elem){
                window.location.replace(\"classCompositionModification.php?action=remove&studentID=\"+elem);
            }
            --></script>";
        $content .=
            "<script type=\"text/javascript\"><!--
        function addStudent(elem){
            window.location.replace(\"classCompositionModification.php?action=add&studentID=\"+elem);
        }
        --></script>";
        $content.="
                    <form>
                    <table class=\"table table-striped\">
                        <thead >
                        <tr>
                            <th scope=\"col\">LastName</th>
                            <th scope=\"col\">Name</th>
                            <th scope=\"col\"></th>
                        </tr>
                        </thead>
                        <tbody>
                    
                    ";
        foreach ($students as $student) {
                    $content.="
                        <tr>
                            <td>".$student['Surname']."</td>
                            <td>".$student['Name']."</td>
                            <td><button class='btn btn-primary' value='Rimuovi Dalla Classe' id=".$student['ID'] ." type='button' onclick='removeStudent(id);'> Remove </button></td>
                        </tr>";
        }
        $content.="
                        </tbody>
                    </table> </form>
                </div>
            </div>";

    // Print now the second table (to add student to class)
    $ustudents = $officer->retrieve_classless_students();
    $content.="
            <div class=\"card mt-md-5\">
                <h2 class=\"card-header info-color white-text text-center py-4\" style=\"background-color:rgba(108,108,108,0.9);color:white\">
                    Students without class
                </h2>
                <div class=\"card-body  px-lg-5 pt-0 mt-md-5\">
                ";
    $content.="
                    <form>
                    <table class=\"table table-striped\">
                        <thead>
                        <tr>
                            <th scope=\"col\">LastName</th>
                            <th scope=\"col\">Name</th>
                            <th scope=\"col\"></th>
                        </tr>
                        </thead>
                        <tbody>
                    
                    ";
        foreach ($ustudents as $student) {
                    $content.="
                        <tr>
                            <td>".$student['Surname']."</td>
                            <td>".$student['Name']."</td>
                            <td><button class='btn btn-primary' value='Aggiungi alla classe' id=".$student['ID'] ." type='button' onclick='addStudent(id);'> Add </button></td>
                        </tr>";
        }
        $content.="
                        </tbody>
                    </table> </form>
                </div>
            </div>";
    }


    $content .= "
    </ul>
";

}else if (isset($_GET['studentID']) && $_GET["action"] == "remove"){
    $classIDReturned = $officer->remove_student_from_class($_GET['studentID']);
    if($classIDReturned!=-1) {
        header("refresh:0.01;url=classCompositionModification.php?classID=" . $classIDReturned);
    }
    // todos handle if fail

}else if (isset($_GET['studentID']) && $_GET["action"] == "add"){
    $succes = $officer->add_student_to_class($_GET['studentID'], $_SESSION["classID"] );
    if($succes) {
        header("refresh:0.01;url=classCompositionModification.php?classID=" . $_SESSION["classID"]);
    }
    // todos handle if fail

}else {
    $content = "<div class=\"card\">

                <h2 class=\"card-header info-color white-text text-center py-4\" style=\"background-color:rgba(108,108,108,0.9);color:white\">
                    Change class composition for:
                </h2>
                <div class=\"card-body  px-lg-5 pt-0 mt-md-5 \">";
    $content .=

        "<script type=\"text/javascript\"><!--
function displayClass(elem){
    window.location.replace(\"classCompositionModification.php?classID=\"+elem);
}
--></script>";
    $content.="
                    <form>
                        <div class=\"input-group \">
                            <select class=\"custom-select\" id=\"inputGroupSelect01\" onchange='displayClass(value);'>
                            <option selected>Choose a class</option>";


    $classes = $officer->get_class_list();

    //if no classes are in the DB, returns to the home
    if (count($classes) == 0) {
        $content = <<<OUT
                        <div class=\"alert alert-danger\" role=\"alert\">
                            There are no classes in DB.<a href=\"index.php\" class=\"alert-link\">Back to your homepage.</a>
                        </div>
OUT;
    } else {
        //for every student, creates the option so that the officer can select which class wants to modify
        foreach ($classes as $class) {
            if($class['ID']!=-1)
                $content .= "<option value=".$class['ID'].">".$class['YearClassID']." ".$class['Section']."</option>";
        }
    }

    $content .= "
                                </select>
                            </div>
                        </form>
                    </div>
                </div>";
}


$page->setContent($content);
$site->render();