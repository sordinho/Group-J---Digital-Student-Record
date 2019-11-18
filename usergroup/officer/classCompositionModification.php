<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Class Composition Modification Page");
$site->setPage($page);

$officer = new officer();

if(!$officer ->is_logged() || $officer ->get_officer_ID()==-1){
	$content = '
    <div class="alert alert-warning" role="warning">
        You are not authorized. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
    </div> ';
	$content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
	$page->setContent($content);
	$site->render();
	exit();
}
else {

    //if a class has been selected loads the list of students of the class so that officer can modify it
    if (isset($_GET['classID'])) {
        $content .= "
    
    <ul class=\"list-group\">";

        $students = $officer->get_Students_By_Class_ID($_GET['classID']);

        if (count($students) == 0) {
            $content = "
                            <div class=\"alert alert-danger\" role=\"alert\">
                             There are no students in the selected Class.<a href=".PLATFORM_PATH."/usergroup/officer/classCompositionModification.php class=\"alert-link\">Back to class selection.</a>
                            </div>
                        ";
        } else {
            $content.="
                <div class=\"card\">";

            $content.="
                    <h5 class=\"card-header info-color white-text text-center py-4\">
                        <strong>Students List</strong>
                    </h5>
                    
                    <div class=\"card-body\">
                    
                    ";
            $content .=

                "<script type=\"text/javascript\"><!--
    function removeStudent(elem){
        window.location.replace(\"classCompositionModification.php?studentID=\"+elem);
    }
    --></script>";
            $content.="
                    
                        <form>
                        
                        <table class=\"table table - sm\">
                          <thead>
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
                              <td><button class='btn btn-primary' value='Rimuovi Dalla Classe' id=".$student['ID'] ." type='button' onclick='removeStudent(id);'> Rimuovi </button></td>
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

    }else if (isset($_GET['studentID'])){
        $classIDReturned = $officer->remove_Student_From_Class($_GET['studentID']);
        if($classIDReturned!=-1) {
            header("refresh:0.01;url=classCompositionModification.php?classID=" . $classIDReturned);
        }
    }else {
        $content = "<div class=\"card\">
                    <div class=\"card-body \">";
        $content .=

            "<script type=\"text/javascript\"><!--
    function displayClass(elem){
        window.location.replace(\"classCompositionModification.php?classID=\"+elem);
    }
    --></script>";
        $content.="
                        <form>
                          <div class=\"input-group \">
                              <div class=\"input-group-prepend\">
                                <label class=\"input-group-text\" for=\"inputGroupSelect01\">Select The Class</label>
                              </div>
                              <select class=\"custom-select\" id=\"inputGroupSelect01\" onchange='displayClass(value);'>
                                <option selected>Choose The Class</option>";


        $classes = $officer->get_Class_List();

        //if no classes are in the DB, returns to the home
        if (count($classes) == 0) {
            $content = <<<OUT
                            <div class=\"alert alert-danger\" role=\"alert\">
                             There are no classes in DB.<a href=\"index.php\" class=\"alert-link\">Back to your homepage.</a>
                            </div>
                        OUT;
        } else {
            //for every student, creates the option so that officer can select which class wants to modify
            foreach ($classes as $class) {
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
}