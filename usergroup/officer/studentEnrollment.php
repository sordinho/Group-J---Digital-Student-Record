<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Upload timetable");
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
			There was a problem enrolling the student <a href="./studentEnrollment.php" class="alert-link">just click here!</a>
    		</div> ';
		$content .= "<meta http-equiv='refresh' content='2' />";
	}
} else

	$content = '

<div class="card">
    <h2 style="background-color:rgba(108,108,108,0.9);color:white" class="card-header info-color white-text text-center py-4">
        Enroll a student
    </h2>
    
    <div class="card-body   px-lg-5 pt-0 mt-md-5">
        <form action="studentEnrollment.php" method="post">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="inputName">First Name</label>
              <input type="text" id="materialRegisterFormFirstName" name="student_first_name" class="form-control" placeholder="Insert Name" required="required">
            </div>
            <div class="form-group col-md-6">
              <label for="inputSurname">Last Name</label>
              <input type="text" id="materialRegisterFormLastName" name="student_last_name" class="form-control"  placeholder="Insert Last Name" required="required">
            </div>
          </div>
          <div class="form-row">
          	<div class="form-group col-md-6">
            	<label for="inputFC">Fiscal Code</label>
            	<input type="text" class="form-control" id="inputFC" name="fiscal_code" placeholder="Insert Fiscal Code" pattern="^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$" required="required">
            	<small id="passwordHelpBlock" class="form-text text-muted">Must be 16 digits.</small>
          	</div>
          	<div class="form-group col-md-6">
            	<label for="inputAverage">Average Last School</label>
            	<input type="number" class="form-control" id="inputAverage" name="average_last_school" placeholder="Insert Average of Last School" min="6" max="10" step="0.25">
            </div>
            <div class="form-group col-md-6">
              <!--<label for="gender">Gender</label>-->
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" checked>
                <label class="form-check-label" for="inlineRadio1">F</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                <label class="form-check-label" for="inlineRadio2">M</label>
              </div>
          	</div>
          </div>
          
          <button type="submit" class="btn btn-primary">Confirm</button>
        </form>
    </div>
</div>';
$page->setContent($content);
$site->render();