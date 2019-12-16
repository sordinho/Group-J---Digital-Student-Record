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
    foreach ($csvAsArray as $key => $row) {
        if($day_n == 0){// the row should be a new class, so let's parse their info
            $year = $row[0];
            $section = $row[1];
            $classID = $officer->get_classID_from_yearSection($year,$section);
            //if($classID == -1)
                //todo
            //$parent_email = $row[2];
            //$child_n = $row[3];
            //$child_N = $child_n; // TODO: remove if the add_new_parent is improved and dont ask also for the input array len
            // Now perform DB actions
            // Try to add a new user with that email, name, surname
            //$userID = $officer->add_new_user($parent_N,$parent_S,$parent_email);
            //if($userID <= 0){
              //  var_dump($row);
                //print($userID);
                //header("Location: uploadCSVParentCredentials.php?operation_result=0");
                //exit();
           // }

            // Now initialize vars and array for children parsing
            $timetable_info = array();
            $day_n = 5;

        } else if ($day_n > 0){
            if (sizeof($row) != 6)
                die("You have an inconsistent csv file");
            $dayid = 5-$day_n;//from 0 to 4
            $timetable_info[$dayid] = array_push($row);
            $day_n--;
            // Please dont move this decrement operation below the if($child_n)
            // Check if we are at the last child, if so perform the parent inserts
            if($day_n == 0){
                // If the insert of user was successful we now need to insert parent with linked FK
                //$res = $officer->add_new_parent($userID,$child_info,$child_n);
                //if(!$res){
                    // On failure use a workaround by trying to remove that user to avoid inconsistent status in the DB
                  //  if(!$officer->remove_user($userID)){
                    //    header("Location: uploadCSVParentCredentials.php?operation_result=-1");
                      //  exit();
                    //}

               // }
                //todo;
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
                Upload list of parent master data
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
