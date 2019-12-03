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


if(!empty($_POST)){
    $parent_N = $_POST['parent_first_name'];
    $parent_S = $_POST['parent_last_name'];
    $parent_email = $_POST['parent_email'];
    $child_N = $_POST['children'];
    $child_info = array();
    for($i = 0; $i<$child_N;$i++){
        $child_info['cf_'.$i] = $_POST['cf_'.$i];
    }
    $userID = $officer->add_new_user($parent_N,$parent_S,$parent_email);
    if($userID <= 0){
        header("Location: uploadParentCredentials.php?operation_result=0");
        die();
    } else {
        // add new parent checks in the db for existing student get the student ID and create a parent record
        // but does *NOT* create an entry in student table
        $res = $officer->add_new_parent($userID,$child_info);
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
        if(isset($_GET['childN']))
            $num = $_GET['childN'];
        $content .= <<<OUT
<!-- Material form register -->
    <script type="text/javascript"><!--
    function displayChildrenForm(elem){
        let childNumber = parseInt(elem.value);
        if(childNumber >= 1){
            window.location.replace("uploadParentCredentials.php?childN="+childNumber);
        }else{
            alert("You can't upload a parent with no childs");
            elem.value = $num;
        }
    }
    --></script>


        <!-- Form -->
        <form class="text-center" style="color: #757575;" action="uploadParentCredentials.php" method="post">
        
        
        
        
        
        
        <div class="card mt-md-5">
					<h4 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">
						Parent
					</h4>
		<div class="card-body  px-lg-5 pt-0 mt-md-5">
            <div class="form-row">
                <div class="col">
                    <!-- First name -->
                    <div class="md-form">
                        <label for="materialRegisterFormFirstName">First name</label>
                        <input type="text" id="materialRegisterFormFirstName" name="parent_first_name" class="form-control">
                    </div>
                </div>
                <div class="col">
                    <!-- Last name -->
                    <div class="md-form">
                        <label for="materialRegisterFormLastName">Last name</label>
                        <input type="text" id="materialRegisterFormLastName" name="parent_last_name" class="form-control">
                    </div>
                </div>
            </div>

            <!-- E-mail -->
            <div class="md-form mt-0">
                <label for="materialRegisterFormEmail">E-mail</label>
                <input type="email" id="materialRegisterFormEmail" name="parent_email" class="form-control">
            </div>
            
            <!-- Children number-->
            <div class="md-form mt-0">
                <label for="materialRegisterFormEmail">Number of children</label>
                <input type="number" id="materialRegisterFormChildNumber" name="children" class="form-control" onchange="displayChildrenForm(this)" value="$num" min="1">
            </div>
            </div>
            </div>

OUT;

        for ($i = 0; $i < $num; $i++) {
            $stud_num = $i+1;
            $content .= <<<OUT
            <div class="card mt-md-5">
					<h4 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">
						#$stud_num Student 
					</h4>
		<div class="card-body  px-lg-5 pt-0 mt-md-5">
            <!-- CF -->
            <div class="md-form mt-0">
                <label for="materialRegisterFormCF$i">Student fiscal code</label>
                <input type="text" id="materialRegisterFormCF$i" name="cf_$i" class="form-control">
            </div>
</div>
</div>

OUT;
        }
        $content .= '
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
