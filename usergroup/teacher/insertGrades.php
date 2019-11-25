<?php
require_once("../../config.php");

$teacher = new teacher();

$site = new csite();
initialize_site($site);
$page = new cpage("Teacher");
$site->setPage($page);

if (!$teacher->is_logged() || $teacher->get_teacher_ID() == -1) {
    $content = '
    <div class="alert alert-warning" role="warning">
        You are not authorized. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
    </div> ';
    $content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
    $page->setContent($content);
    $site->render();
    exit();
}
$classes = $teacher->get_assigned_classes();
$drop_down = "";
for ($i = 0; $i < sizeof($classes); $i++) {
    $classID = $classes[$i]['classID'];
    $yearSection = $classes[$i]['YearClass'] . " " . $classes[$i]['Section'];
    $drop_down .= <<<OUT
                    <a class="dropdown-item" href="insertGrades.php?classID=$classID">$yearSection</a>
                    OUT;
}
if (!isset($_GET['classID'])) {

    $content = <<<OUT
                <div class="card text-center">
                  <div class="card-header">
                    Choose a class :
                    <div class="btn-group">
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Class
                  </button>
                  <div class="dropdown-menu">
                    $drop_down
                  </div>
                </div>
                  </div>
                  <div class="card-body">
                    <p class="card-text">Select a class to insert a new grade.</p>
                  </div>
                </div>
                
                OUT;
} else if (isset($_GET['classID'])) {

    if (!isset($_GET['studentID'])) {
        $students_info = $teacher->get_students_by_class_id($_GET['classID']);
        $table_content = '<div class="card-body">
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
                      <tbody>';

        for ($i = 0; $i < sizeof($students_info); $i++) {
            $name = $students_info[$i]['Name'];
            $surname = $students_info[$i]['Surname'];
            $id = $students_info[$i]['ID'];
            $table_content .= <<<OUT
                            <tr>
                                <th scope="row">$i</th>
                                  <td>$surname</td>
                                  <td>$name</td>
                                  <td>
                                  <div class="md-form col-md-4">
                                    <input type="number" id="materialRegisterFormGradeStudent$id" name="grade_$id" class="form-control" step="0.25">
                                    <input type="text" id="materialRegisterFormStudentID$id" name="studentID" class="form-control" value="$id" hidden>
                                  </div>
                            </td>
                            </tr>
                            OUT;
        }

        $table_content .= '</tbody>
                        </table>
                        <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Submit</button>
                        </form>
                          </div>
                        </div>';
        $content = <<<OUT
                <div class="card text-center">
                  <div class="card-header">
                    You're in class $classID.
                    <div class="btn-group">
                      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Change class
                      </button>
                      <div class="dropdown-menu">
                        $drop_down
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                  $table_content
                OUT;
    }
}
$page->setContent($content);
$site->render();
