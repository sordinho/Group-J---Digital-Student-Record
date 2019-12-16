<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Register new parent");
$site->setPage($page);
$officer = new officer();

$num = 1;
$content="";
if(!$officer ->is_logged() ){
    header("location: /error.php?errorID=19");
    exit();
}

if ( isset($_POST) && isset($_FILES["file"])) {
    //Check if there was an error uploading the file
    if ($_FILES["file"]["error"] > 0 ||  $_FILES["file"]["type"] != 'text/csv') {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
        exit();
    }

    // Load uploaded file and map its content to an array
    $tmpName = $_FILES['file']['tmp_name'];
    $csvAsArray = array_map('str_getcsv', file($tmpName));
    # csv format should be as following:
    # <class Year, class Section>
    # <h1, h2, h3, h4, h5, h6> x 5
    # <class Year, class Section>
    # ...
    $day_n = 0;   // Child number that needs to be parsed for a given parent (*not constant*)
    $da = 0;
    $classID = -1;
    $topic_teacher_info = array();
    $action = "";
    foreach ($csvAsArray as $key => $row) {
        if($day_n == 0){// the row should be a new class, so let's parse their info
            $year = $row[0];
            $section = $row[1];
            $classID = $officer->get_classID_from_yearSection($year,$section); // classID || -1
            $timetable_info = array();
            if($classID!=-1) { // se ho trovato una classe con quell'anno e quella sezione
                $topic_teacher_info = $officer->get_teacher_topic($classID); //prendo tutte le info di professori-topic per quella classe
                if(exists_timetable($classID)){
                    $action="update";
                } else {
                    $action="insert";
                }
            }
            $day_n = 5; //inizializzo il contatore a 5

        } else if ($day_n > 0){ //se sto ancora parsificando i giorni della settimana per una data classe
            if (sizeof($row) != 6)
                die("You have an inconsistent csv file");
            if($classID != -1) { // se la classe esiste
                $found = false;
                $dayid = 5 - $day_n;//from 0 to 4

                $str = "";
                for($j = 0; $j< 6 ; $j++){ // per ogni ora della settimana --> row[j] = nome della materia
                    $found = false;
                    for($i = 0; $i< sizeof($topic_teacher_info)&&!$found;$i++){ //per ogni entry delle info su topic-teachers
                        if($topic_teacher_info[$i]['TopicName'] == $row[$j]){ //se ho trovato quella materia allora ho anche il topicID e il teacher id
                            $str = $topic_teacher_info[$i]['TopicID']."|".$topic_teacher_info[$i]['TeacherID']."|".$action; // riempio quello che andrà nella casella della matrice
                            $found=true;
                        }
                    }
                    if($found) //se ho trovato quella materia e ho riempito str
                        $timetable_info[$dayid][$j]=$str; //metto str nella matrice
                    else{
                        $classID=-1; //todo : cosa succede se non esiste quella materia per quella classe? al momento semplicemente ignoro il resto
                                    //  della timetable e non la carico... da modificare
                        break;
                    }
                }
            }
            $day_n--; //decremento il contatore
            if($day_n == 0 && $classID!= -1){ //todo scommentare quando la funzione c'è
                //$res = $officer->set_timetable_class($classID,$timetable_info);
                //if(!$res){
                //    header("Location: uploadCSVParentCredentials.php?operation_result=-1");
                //    exit();
                //}
            }
        }else{
            die("An error occurred in csv parsing");
        }
    }
    header("Location: uploadCSVParentCredentials.php?operation_result=1");
    exit();
} else {
    if(isset($_GET['operation_result'])){
        switch ($_GET['operation_result']){
            case 1:
                $content.= '
                            <div class="alert alert-success" role="alert">
                                Parent successfully uploaded. Go <a href="index.php" class="alert-link">back to your homepage.</a>
                            </div>';
                break;
            case 0:
                $content.= '
                            <div class="alert alert-danger" role="alert">
                                Error in uploading parent\'s master data. <a href="uploadCSVParentCredentials.php" class="alert-link">Retry </a> or <a href="../officer/index.php" class="alert-link">back to your homepage.</a>
                            </div>';

                break;
            case -1:
                $content.= '
                            <div class="alert alert-danger" role="alert">
                                Inconsistency on the DB error, contact the database administrator. <a href="../officer/index.php" class="alert-link">Back to your homepage.</a>
                            </div>';

                break;
            default:
                $content.='
                        <div class="alert alert-dark" role="alert">
                            Operation not allowed.
                        </div>';
        }
    }
    // If no request (POST) was performed, and no operation_result is set, render the GUI FORM
    else {
        $content .= '
        <!-- Material form register -->
        <div class="card">
            <h4 style="background-color:rgba(108,108,108,0.9);color:white" class="card-header info-color white-text text-center py-4">
                Upload list timetables
            </h4>

            <!--Card content-->
            <div class="card-body px-lg-5 pt-0">

                <!-- Form -->
                <form class="text-center" style="color: #757575;" action="uploadCSVParentCredentials.php" enctype="multipart/form-data" method="post">
                    <p class="card-body info-color white-text text-center py-4">CSV upload</p>
                    <div class="form-row">
                        <div class="col">
                            <!-- First name -->
                            <div class="md-form">
                                <input type="file" id="file" name="file" class="form-control-file">
                                <!--<label for="CSVUpload">CSV Upload</label>-->
                            </div>
                        </div>
                <!-- Sign up button -->
                <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Submit</button>
                </form>
                <!-- Form -->
                </div> 
                </div>';
    }
}

$page->setContent($content);
$site->render();
