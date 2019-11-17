<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Class Composition Modification Page");
$site->setPage($page);

$officer = new officer();
if(!$officer->get_officer_ID()){
    $content = '
        <div class="alert alert-warning" role="warning">
            You are not authorized. If you are in a hurry <a href="index.php" class="alert-link">just click here!</a>
        </div> ';
    $content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
    $page->setContent($content);
    $site->render();
    render_page($content, '');
}// should call get_error TODOs:
else {


    if (isset($_GET['classID'])) {
        $content .= "
    
    <ul class=\"list-group\">";

        $students = $officer->get_Students_By_Class_ID($_GET['classID']);

        if (count($students) == 0) {
            $content = <<<OUT
                            <div class=\"alert alert-danger\" role=\"alert\">
                             There are no students in the selected Class.<a href=\"index.php\" class=\"alert-link\">Back to your homepage.</a>
                            </div>
                        OUT;
        } else {
            $content.="
                <div class=\"card\">";

            $content.="
                    <h5 class=\"card-header info-color white-text text-center py-4\">
                        <strong>Class Modification</strong>
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
                        ";
            foreach ($students as $student) {
                        $content.="<li class=\"list-group-item\" value=".$student['ID'].">".$student['Surname']." ".$student['Name']."
                                        <button value='Rimuovi Dalla Classe' id=".$student['ID'] ." type='button' onclick='removeStudent(id);'> Rimuovi </button>
                                    </li>";
            }
            $content.=" </form>
                    </div>
                </div>";
        }


        $content .= "
        </ul>
";

    }else if (isset($_GET['studentID'])){


        $officer->remove_Student_From_Class($_GET['studentID']);
        header( "refresh:2;url=classCompositionModification.php" );



    }else {


        $content = "<div class=\"card\">";



        $content .= "
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

        if (count($classes) == 0) {
            $content = <<<OUT
                            <div class=\"alert alert-danger\" role=\"alert\">
                             There are no classes in DB.<a href=\"index.php\" class=\"alert-link\">Back to your homepage.</a>
                            </div>
                        OUT;
        } else {
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