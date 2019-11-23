<?php
require_once("../../config.php");

$teacherObj = new teacher();

$site = new csite();
initialize_site($site);
$page = new cpage("Teacher");
$site->setPage($page);

/*if (!$teacher->is_logged() || $teacher->get_teacher_ID() == -1) {
    $content = '
    <div class="alert alert-warning" role="warning">
        You are not authorized. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
    </div> ';
    $content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
    $page->setContent($content);
    $site->render();
    exit();
}*/

//classes = teacher->get_all_classes() <--- mi serviranno ID, YearClassID, Section da SpecificClass
//for( class : classes ){
// fill the content of the dropdownMenu, the href will be "insertGrades?classID=retrieved class id from getAllClasses"
// the content will be $year_class_id." ".$section
//}
if(!isset($_GET['classID'])) {

    $content = <<<OUT
<div class="card text-center">
  <div class="card-header">
    Choose a class :
    <div class="btn-group">
  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Class
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="insertGrades.php?classID=1">class1</a>
    <a class="dropdown-item" href="#">class2</a>
    <a class="dropdown-item" href="#">class3</a>
  </div>
</div>
  </div>
  <div class="card-body">
    <p class="card-text">Select a class to insert a new grade.</p>
  </div>
</div>

OUT;
} else if (isset($_GET['classID'])) {
    //todo get students and fill the table
    //
    $content = <<<OUT
<div class="card text-center">
  <div class="card-header">
    You're in class classID.
    <div class="btn-group">
  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Change class
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="insertGrades.php?classID=1">class1</a>
    <a class="dropdown-item" href="#">class2</a>
    <a class="dropdown-item" href="#">class3</a>
  </div>
</div>
  </div>
  <div class="card-body">
  <form method="post" style="color:#757575">
    <table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Last Name</th>
      <th scope="col">First Name</th>
      <th scope="col">Insert Grade</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>AstudentLast</td>
      <td>AstudentFirst</td>
      <td>
      <div class="md-form col-md-4">
        <input type="number" id="materialRegisterFormGradeStudentA" name="studentIDgrade" class="form-control" step="0.25">
      </div>
</td>
    </tr>
    <tr>
<th scope="row">2</th>
      <td>BstudentLast</td>
      <td>BstudentFirst</td>
      <td>
      <div class="md-form col-md-4">
        <input type="number" id="materialRegisterFormGradeStudentB" name="studentIDgrade" class="form-control">
      </div>
</td>
    </tr>
    <tr>
     <th scope="row">3</th>
      <td>CstudentLast</td>
      <td>CstudentFirst</td>
      <td>
      <div class="md-form col-md-4">
        <input type="number" id="materialRegisterFormGradeStudentC" name="studentIDgrade" class="form-control">
      </div>
</td>
    </tr>
  </tbody>
</table>
<button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Submit</button>
</form>
  </div>
</div>

OUT;
}
$page->setContent($content);
$site->render();
