<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Register new parent");
$site->setPage($page);
$officer = new officer();

$num = 1;
$content="";

// TODOS: [Improvement] 
// - Consider what happens if in the middle of csv parsing a query fails (a full rollback seems too hard and useless to accomplish)
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
    #<fn, ln, pemail, childrenN>
    # <child1 CF>
    # ...
    # < childN CF>
    //var_dump($csvAsArray);
    $child_n = 0;   // Child number that needs to be parsed for a given parent (*not constant*)
    $child_count = 0;
    foreach ($csvAsArray as $key => $row) {
        if($child_n == 0){// the row should be a parent, so let's parse their info
            $parent_N = $row[0];
            $parent_S = $row[1];
            $parent_email = $row[2];
            $child_n = $row[3];
            $child_N = $child_n; // TODO: remove if the add_new_parent is improved and dont ask also for the input array len
            // Now perform DB actions
            // Try to add a new user with that email, name, surname
            $userID = $officer->add_new_user($parent_N,$parent_S,$parent_email);
            if($userID <= 0){
                var_dump($row);
                print($userID);
                header("Location: uploadCSVParentCredentials.php?operation_result=0");
                exit();
            }
            
            // Now initialize vars and array for children parsing
            $child_info = array();
            $child_count = 0;

        }elseif($child_n > 0){
            if (sizeof($row) != 1)  # after a parent with N children there should be 5 record with their Fiscal code (no more no less)
                die("You have an inconsistent csv file");
            $child_info['cf_'.$child_count] = $row[0];
            $child_count++;
            $child_n--;     // Please dont move this decrement operation below the if($child_n)
            // Check if we are at the last child, if so perform the parent inserts
            if($child_n == 0){
                // If the insert of user was successful we now need to insert parent with linked FK
                $res = $officer->add_new_parent($userID,$child_info,$child_N);
                if(!$res){
                    // On failure use a workaround by trying to remove that user to avoid inconsistent status in the DB
                    if(!$officer->remove_user($userID)){
                        header("Location: uploadCSVParentCredentials.php?operation_result=-1");
                        exit();
                    }
                    
                }
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
            <h5 class="card-header info-color white-text text-center py-4">
                <strong>Upload list of parent master data</strong>
            </h5>

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
/*


if ( isset($_POST["submit"]) ) {

    if ( isset($_FILES["file"])) {

            //if there was an error uploading the file
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

        }
        else {
                 //Print file details
            //echo "Upload: " . $_FILES["file"]["name"];
            //echo "Type: " . $_FILES["file"]["type"]";
            //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb";
            //echo "Temp file: " . $_FILES["file"]["tmp_name"];

$tmpName = $_FILES['csv']['tmp_name'];
$csvAsArray = array_map('str_getcsv', file($tmpName));
*/