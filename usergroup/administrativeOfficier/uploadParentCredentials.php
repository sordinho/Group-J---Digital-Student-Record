<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Register new parent");
$site->setPage($page);
$num = 1;
if(!empty($_POST)){
    $content = "OK";
    $parent_N = $_POST['materialRegisterFormFirstName'];
    $parent_S = $_POST['materialRegisterFormLastName'];
    $parent_email = $_POST['materialRegisterFormEmail'];
    $child_N = $_POST['materialRegisterFormChildNumber'];
    $content.="<p>Name: ".$parent_N."</p>";
    $content.="<p>Surname: ".$parent_S."</p>";
    $content.="<p>Email: ".$parent_email."</p>";
    $content.="<p>Child#: ".$child_N."</p>";
    $child_info = array();
    for($i = 0; $i<$child_N;$i++){
        $child_info['FirstName'.$i]= $_POST['materialRegisterFormFirstNameChild'.$i];
        $child_info['LastName'.$i]= $_POST['materialRegisterFormLastNameChild'.$i];
        $child_info['CF'.$i] = $_POST['materialRegisterFormCF'.$i];
        $content.="<p>Name: ".$child_info['FirstName'.$i].'</p>';
        $content.="<p>Surname: ".$child_info['LastName'.$i].'</p>';
        $content.="<p>CF: ".$child_info['CF'.$i].'</p>';

    }
    //$content="";

}else{
    if (isset($_GET['childN']))
        $num = $_GET['childN'];
    $content = <<<OUT
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
                        <input type="text" id="materialRegisterFormFirstName" class="form-control">
                        <label for="materialRegisterFormFirstName">First name</label>
                    </div>
                </div>
                <div class="col">
                    <!-- Last name -->
                    <div class="md-form">
                        <input type="text" id="materialRegisterFormLastName" class="form-control">
                        <label for="materialRegisterFormLastName">Last name</label>
                    </div>
                </div>
            </div>

            <!-- E-mail -->
            <div class="md-form mt-0">
                <input type="email" id="materialRegisterFormEmail" class="form-control">
                <label for="materialRegisterFormEmail">E-mail</label>
            </div>
            
            <!-- Children number-->
            <div class="md-form mt-0">
                <input type="number" id="materialRegisterFormChildNumber" class="form-control" onchange="displayChildrenForm(this)" value="$num">
                <label for="materialRegisterFormEmail">Number of children</label>
            </div>
OUT;

    for ($i = 0; $i < $num; $i++) {
        $content .= <<<OUT
            <hr>
            
            <p class="card-body info-color white-text text-center py-4">Child $i</p>
            <div class="form-row">
                <div class="col">
                    <!-- First name -->
                    <div class="md-form">
                        <input type="text" id="materialRegisterFormFirstNameChild$i" class="form-control">
                        <label for="materialRegisterFormFirstName">Child first name</label>
                    </div>
                </div>
                <div class="col">
                    <!-- Last name -->
                    <div class="md-form">
                        <input type="text" id="materialRegisterFormLastNameChild$i" class="form-control">
                        <label for="materialRegisterFormLastName">Child last name</label>
                    </div>
                </div>
            </div>
            
            <!-- CF -->
            <div class="md-form mt-0">
                <input type="text" id="materialRegisterFormCF$i" class="form-control">
                <label for="materialRegisterFormEmail">Child fiscal code</label>
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
    /*here content*/

//1 get generated credentials
// $officier = new AdministrativeOfficier();
// $res = $officier->getGeneratedCredentials();
//2 show generated credentials in a tabular fashion
// while($row = $res->fetch_row()){
//      print a table
// }
//3 confirm the operation
// ask for user confirmation
//3.a send mail
// IF user confirms --> notify mailer class
}
$page->setContent($content);
$site->render();
