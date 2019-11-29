<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Student Enrollment Page");
$site->setPage($page);
$officer = new officer();

if (!$officer->is_logged() ) {
	header("location: /error.php?errorID=19");
	exit();
}

if (!empty($_POST)) {
	$studentInfo = array();
	$studentInfo['name'] = $_POST['student_first_name'];
	$studentInfo['surname'] = $_POST['student_last_name'];
	$studentInfo['avgLastSchool'] = $_POST['average_last_school'];
	$studentInfo['CF'] = $_POST['fiscal_code'];

	if ($officer->enroll_student($studentInfo)) {
		$content = '
			<div class="alert alert-success" role="warning">
			Student enrolled <a href="./studentEnrollment.php" class="alert-link">just click here!</a>
    		</div> ';
		$content .= "<meta http-equiv='refresh' content='2'/>";
	} else {
		$content = '
			<div class="alert alert-danger" role="warning">
			There was a problem enrollin the student <a href="./studentEnrollment.php" class="alert-link">just click here!</a>
    		</div> ';
		$content .= "<meta http-equiv='refresh' content='2' />";
	}
} else

	$content = "

<div class=\"card\">
    <h5 class=\"card-header info-color white-text text-center py-4\">
        <strong>Enter Student Master Data</strong>
    </h5>
    
    <div class=\"card-body px-lg-5 pt-0\">
        <p class=\"card-body info-color white-text text-center py-4\">Student</p>
        <form action='studentEnrollment.php' method='post'>
          <div class=\"form-row\">
            <div class=\"form-group col-md-6\">
              <label for=\"inputName\">First Name</label>
              <input type=\"text\" id=\"materialRegisterFormFirstName\" name=\"student_first_name\" class=\"form-control\" placeholder=\"Insert Name\">
            </div>
            <div class=\"form-group col-md-6\">
              <label for=\"inputSurname\">Last Name</label>
              <input type=\"text\" id=\"materialRegisterFormLastName\" name=\"student_last_name\" class=\"form-control\"  placeholder=\"Insert Last Name\">
            </div>
          </div>
          <div class=\"form-row\">
          	<div class=\"form-group col-md-6\">
            	<label for=\"inputFC\">Fiscal Code</label>
            	<input type=\"text\" class=\"form-control\" id=\"inputFC\" name=\"fiscal_code\" placeholder=\"Insert Fiscal Code\">
            	<small id=\"passwordHelpBlock\" class=\"form-text text-muted\">Must be 16 digits.</small>
          	</div>
          	<div class=\"form-group col-md-6\">
            	<label for=\"inputAverage\">Average Last School</label>
            	<input type=\"number\" class=\"form-control\" id=\"inputAverage\" name=\"average_last_school\" placeholder=\"Insert Average of Last School\" min='6' max='10' step='0.25'>
            </div>
            <div class=\"form-group col-md-6\">
              <!--<label for=\"gender\">Gender</label>-->
              <div class='form-check form-check-inline'>
                <input class='form-check-input' type='radio' name='inlineRadioOptions' id='inlineRadio1' value='option1'>
                <label class='form-check-label' for='inlineRadio1'>F</label>
              </div>
              <div class='form-check form-check-inline'>
                <input class='form-check-input' type='radio' name='inlineRadioOptions' id='inlineRadio2' value='option2'>
                <label class='form-check-label' for='inlineRadio2'>M</label>
              </div>
          	</div>
          </div>
          
          <button type=\"submit\" class=\"btn btn-primary\">Confirm</button>
        </form>
    </div>
</div>";
$page->setContent($content);
$site->render();