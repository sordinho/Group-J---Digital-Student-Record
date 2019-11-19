<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Register new parent");
$site->setPage($page);
$officer = new officer();

$num = 1;
$content="";

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


if(!empty($_POST)){
    $parent_N = $_POST['parent_first_name'];
    $parent_S = $_POST['parent_last_name'];
    $parent_email = $_POST['parent_email'];
    $child_N = $_POST['children'];
    $child_info = array();
    for($i = 0; $i<$child_N;$i++){
        $child_info['first_name_'.$i]= $_POST['first_name_child_'.$i];
        $child_info['last_name_'.$i]= $_POST['last_name_child_'.$i];
        $child_info['cf_'.$i] = $_POST['cf_'.$i];
    }
    $userID = $officer->add_new_user($parent_N,$parent_S,$parent_email);
    if($userID <= 0){
        header("Location: uploadParentCredentials.php?operation_result=0");
        die();
    } else {
        // add new parent checks in the db for existing student get the student ID and create a parent record
        // but does *NOT* create an entry in student table
        $res = $officer->add_new_parent($userID,$child_info,$child_N);
        if(!$res){
            if(!$officer->remove_user($userID)){
                header("Location: uploadParentCredentials.php?operation_result=-1");
                die();
            }
            header("Location: uploadParentCredentials.php?operation_result=0");
            die();
        }
        header("Location: uploadParentCredentials.php?operation_result=1");
        die();
    }
}else{
    if(isset($_GET['operation_result'])){
        switch ($_GET['operation_result']){
            case 1:
                $content.= <<<OUT
<div class="alert alert-success" role="alert">
  Parent successfully uploaded. <a href="uploadParentCredentials.php" class="alert-link">Add another parent</a> or <a href="index.php" class="alert-link">back to your homepage.</a>
</div>
OUT;
                break;
            case 0:
                $content.= <<<OUT
<div class="alert alert-danger" role="alert">
 Error in uploading parent's master data. <a href="uploadParentCredentials.php" class="alert-link">Retry </a> or <a href="../officer/index.php" class="alert-link">back to your homepage.</a>
</div>
OUT;

                break;
            case -1:
                $content.= <<<OUT
<div class="alert alert-danger" role="alert">
 Fatal error, contact the database administrator. <a href="../officer/index.php" class="alert-link">Back to your homepage.</a>
</div>
OUT;

                break;
            default:
                $content.=<<<OUT
<div class="alert alert-dark" role="alert">
  Operation not allowed.
</div>
OUT;
        }


    } else {
        if (isset($_GET['childN']))
            $num = $_GET['childN'];
        $content .= <<<OUT
<!-- Material form register -->
<div class="card">
    <script type="text/javascript"><!--
    function displayChildrenForm(elem){
        let childNumber = parseInt(elem.value);
        window.location.replace("uploadParentCredentials.php?childN="+childNumber);
    }
    --></script>
    <h5 class="card-header info-color white-text text-center py-4">
        <strong>Enter parent master data</strong>
    </h5>

    <!--Card content-->
    <div class="card-body px-lg-5 pt-0">

        <!-- Form -->
        <form class="text-center" style="color: #757575;" action="uploadParentCredentials.php" method="post">
            <p class="card-body info-color white-text text-center py-4">Parent</p>
            <div class="form-row">
                <div class="col">
                    <!-- First name -->
                    <div class="md-form">
                        <input type="text" id="materialRegisterFormFirstName" name="parent_first_name" class="form-control">
                        <label for="materialRegisterFormFirstName">First name</label>
                    </div>
                </div>
                <div class="col">
                    <!-- Last name -->
                    <div class="md-form">
                        <input type="text" id="materialRegisterFormLastName" name="parent_last_name" class="form-control">
                        <label for="materialRegisterFormLastName">Last name</label>
                    </div>
                </div>
            </div>

            <!-- E-mail -->
            <div class="md-form mt-0">
                <input type="email" id="materialRegisterFormEmail" name="parent_email" class="form-control">
                <label for="materialRegisterFormEmail">E-mail</label>
            </div>
            
            <!-- Children number-->
            <div class="md-form mt-0">
                <input type="number" id="materialRegisterFormChildNumber" name="children" class="form-control" onchange="displayChildrenForm(this)" value="$num">
                <label for="materialRegisterFormEmail">Number of children</label>
            </div>
OUT;

        for ($i = 0; $i < $num; $i++) {
            $content .= <<<OUT
            <hr>
            
            <p class="card-body info-color white-text text-center py-4">Student $i</p>
            
            
            <!-- CF -->
            <div class="md-form mt-0">
                <input type="text" id="materialRegisterFormCF$i" name="cf_$i" class="form-control">
                <label for="materialRegisterFormEmail">Student fiscal code</label>
            </div>
OUT;
        }
        $content .= <<<OUT
    <!-- Sign up button -->
            <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Submit</button>

        </form>
        <!-- Form -->

    </div>

</div>
<!-- Material form register -->
OUT;
    }
}
$page->setContent($content);
$site->render();
