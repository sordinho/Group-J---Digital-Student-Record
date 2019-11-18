<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Student Enrollment Page");
$site->setPage($page);
$officer = new officer();

if (!$officer->is_logged() || $officer->get_officer_ID() == -1) {
	$content = '
    <div class="alert alert-warning" role="warning">
        You are not authorized. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
    </div> ';
	$content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
	$page->setContent($content);
	$site->render();
	exit();
}

if (!empty($_POST)) {
	$studentInfo = array();
	$studentInfo['name'] = $_POST['student_first_name'];
	$studentInfo['surname'] = $_POST['student_last_name'];
	$studentInfo['avgLastSchool'] = $_POST[''];
	$studentInfo['CF'] = $_POST['fiscal_code'];

	if ($officer->enroll_student($studentInfo)) {
		$ciao = 0; // TODO only for test
	} else {
		$content = '
			<div class="alert alert-warning" role="warning">
			There was a problem enrollin the student<a href="./studentEnrollment.php" class="alert-link">just click here!</a>
    		</div> ';
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
            	<input type=\"number\" class=\"form-control\" id=\"inputAverage\" name=\"average_last_school\" placeholder=\"Insert Average of Last School\" min='6' max='10'>
          	</div>
          </div>
          
          <button type=\"submit\" class=\"btn btn-primary\">Confirm</button>
        </form>
    </div>
</div>";

$page->setContent($content);
$site->render();