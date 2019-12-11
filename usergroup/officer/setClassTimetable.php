<?php

require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("");
$site->setPage($page);

$officer = new officer();

if (!$officer->is_logged()) {
    header("location: /error.php?errorID=19");
    exit();
}

if (!empty($_POST)) {
    $teacher_hour_day['hours'] = array();

    /*
     *  Select name guide
     *  00  10  20  30  40
     *  01  11  21  31  41
     *  02  12  22  32  42
     *  03  13  23  33  43
     *  04  14  24  34  44
     *  05  15  25  35  45
     */

    $teacher_hour_day['hours'][0] = array();
    $teacher_hour_day['hours'][0][0]= $_POST['select00'];
    $teacher_hour_day['hours'][0][1]= $_POST['select01'];
    $teacher_hour_day['hours'][0][2]= $_POST['select02'];
    $teacher_hour_day['hours'][0][3]= $_POST['select03'];
    $teacher_hour_day['hours'][0][4]= $_POST['select04'];
    $teacher_hour_day['hours'][0][5]= $_POST['select05'];

    $teacher_hour_day['hours'][1] = array();
    $teacher_hour_day['hours'][1][0]= $_POST['select10'];
    $teacher_hour_day['hours'][1][1]= $_POST['select11'];
    $teacher_hour_day['hours'][1][2]= $_POST['select12'];
    $teacher_hour_day['hours'][1][3]= $_POST['select13'];
    $teacher_hour_day['hours'][1][4]= $_POST['select14'];
    $teacher_hour_day['hours'][1][5]= $_POST['select15'];

    $teacher_hour_day['hours'][2] = array();
    $teacher_hour_day['hours'][2][0]= $_POST['select20'];
    $teacher_hour_day['hours'][2][1]= $_POST['select21'];
    $teacher_hour_day['hours'][2][2]= $_POST['select22'];
    $teacher_hour_day['hours'][2][3]= $_POST['select23'];
    $teacher_hour_day['hours'][2][4]= $_POST['select24'];
    $teacher_hour_day['hours'][2][5]= $_POST['select25'];

    $teacher_hour_day['hours'][3] = array();
    $teacher_hour_day['hours'][3][0]= $_POST['select30'];
    $teacher_hour_day['hours'][3][1]= $_POST['select31'];
    $teacher_hour_day['hours'][3][2]= $_POST['select32'];
    $teacher_hour_day['hours'][3][3]= $_POST['select33'];
    $teacher_hour_day['hours'][3][4]= $_POST['select34'];
    $teacher_hour_day['hours'][3][5]= $_POST['select35'];

    $teacher_hour_day['hours'][4] = array();
    $teacher_hour_day['hours'][4][0]= $_POST['select40'];
    $teacher_hour_day['hours'][4][1]= $_POST['select41'];
    $teacher_hour_day['hours'][4][2]= $_POST['select42'];
    $teacher_hour_day['hours'][4][3]= $_POST['select43'];
    $teacher_hour_day['hours'][4][4]= $_POST['select44'];
    $teacher_hour_day['hours'][4][5]= $_POST['select45'];

    $teacher_hour_day['classID']=$_SESSION['classID'];

    if ($officer->setTimeTableClass($teacher_hour_day)) {
        $content = '
			<div class="alert alert-success" role="warning">
			Timetable saved correctly <a href="./setClassTimetable.php" class="alert-link">just click here!</a>
    		</div> ';
        $content .= "<meta http-equiv='refresh' content='2'/>";
    } else {
        $content = '
			<div class="alert alert-danger" role="warning">
			There was a problem saving timetables <a href="./setClassTimetable.php" class="alert-link">just click here!</a>
    		</div> ';
        $content .= "<meta http-equiv='refresh' content='2' />";
    }
}

//if a class has been selected loads the list of students of the class so that the officer can modify it
else if (isset($_GET['classID'])) {

    $professorsTopics=$officer->get_teacher_topic($_GET['classID']);
    $optionList="<option selected>Choose a topic</option>";
    foreach ($professorsTopics as $professorTopic) {
            $optionList .= "<option value=" . $professorTopic[3] . "_". $professorTopic[4].">" . $professorTopic[2] . " - ". $professorTopic[1] ."</option>";
    }

    $_SESSION['classID']=$_GET['classID'];
    $content .= '
                <div class="card">

                    <h2 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">
                        Set Timetable for class: '.$officer->get_class_stamp_by_id($_GET['classID']).'
                    </h2>
                    <div class="card-body  px-lg-5 pt-0 mt-md-5 ">

                    
                    <form action="setClassTimetable.php" method="post">
                        <div class="input-group ">

                                    <table class="table table-bordered table-striped">
                                      <thead>
                                        <tr>
                                          <th></th>
                                          <th class="text-center">Monday</th>
                                          <th class="text-center">Tuesday</th>
                                          <th class="text-center">Wednesday</th>
                                          <th class="text-center">Thursday</th>
                                          <th class="text-center">Friday</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <th class="text-center" scope="row">8:00</th>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect00" name="select00">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect10" name="select10">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect20" name="select20">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect30" name="select30">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect40" name="select40">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                        </tr>
                                        <tr>
                                          <th class="text-center" scope="row">9:00</th>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect01" name="select01">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect11" name="select11">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect21" name="select21">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect31" name="select31">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect41" name="select41">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                        </tr>
                                        <tr>
                                          <th class="text-center" scope="row">10:00</th>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect02" name="select02">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect12" name="select12">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect22" name="select22">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect32" name="select32">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect42" name="select42">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                        </tr>
                                        <tr>
                                          <th class="text-center" scope="row">11:00</th>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect03" name="select03">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect13" name="select13">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect23" name="select23">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect33" name="select33">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect43" name="select43">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                        </tr>
                                        <tr>
                                          <th class="text-center" scope="row">12:00</th>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect04" name="select04">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect14" name="select14">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect24" name="select24">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect34" name="select34">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect44" name="select44">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                        </tr>
                                        <tr>
                                          <th class="text-center" scope="row">13:00</th>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect05" name="select05">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect15" name="select15">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect25" name="select25">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect35" name="select35">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                          <td>
                                              <select class="custom-select" id="inputGroupSelect45" name="select45">
                                                '.$optionList.'
                                              </select>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Confirm</button>
                                    </form>
                                    </div>
                                    </div>
                                    '
;

}
else if (isset($_GET['studentID']) && $_GET["action"] == "remove") {
    /*$classIDReturned = $officer->remove_student_from_class($_GET['studentID']);
    if ($classIDReturned != -1) {
        header("refresh:0.01;url=setClassTimetable.php?classID=" . $classIDReturned);
    }*/
    // todos handle if fail

}
else if (isset($_GET['studentID']) && $_GET["action"] == "add") {
    /*$succes = $officer->add_student_to_class($_GET['studentID'], $_SESSION["classID"]);
    if ($succes) {
        header("refresh:0.01;url=classCompositionModification.php?classID=" . $_SESSION["classID"]);
    }*/
    // todos handle if fail

}
else {
    $content = '<div class="card">

                <h2 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">
                    Change class composition for:
                </h2>
                <div class="card-body  px-lg-5 pt-0 mt-md-5 ">';
    $content .=

        '<script type="text/javascript">
            <!--
            function displayClass(elem){
                window.location.replace("setClassTimetable.php?classID="+elem);
            }
            -->
        </script>';
    $content .= '
                    <form>
                        <div class="input-group ">
                            <select class="custom-select" id="inputGroupSelect01" onchange="displayClass(value);">
                            <option selected>Choose a class</option>';


    $classes = $officer->get_class_list();

    //if no classes are in the DB, returns to the home
    if (count($classes) == 0) {
        $content .= '
                        <div class="alert alert-danger" role="alert">
                            There are no classes in DB.<a href="index.php" class="alert-link">Back to your homepage.</a>
                        </div>';
    } else {
        //for every student, creates the option so that the officer can select which class wants to modify
        foreach ($classes as $class) {
            if ($class['ID'] != -1)
                $content .= "<option value=" . $class['ID'] . ">" . $class['YearClassID'] . " " . $class['Section'] . "</option>";
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

