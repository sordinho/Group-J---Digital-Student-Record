<?php

require_once("../../config.php");


$site = new csite();
initialize_site($site);
$page = new cpage("");
$site->setPage($page);
$sparent = new sparent();

if (!$sparent->is_logged() ) {
    header("location: /error.php?errorID=19");
    exit();
}
# If user is correctly authenticated:
$cur_child = $sparent->get_current_child();

if ($cur_child == -1) {
    $hidden_warning = '
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
			  <h4 class="alert-heading">Hello!</h4>
			  <p>Please, <strong>select a student</strong> you want to operate on from the sidebar.</p>
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
			  <hr>
			  <p class="mb-0">After selected a student, you can access all the functionalities in the sidebar.</p>
			</div>';

    $content = $hidden_warning;
} else if(isset($_GET['termID'])){

    $content = '<ul class="list-group">
            <div class="card">
                <h3 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">
                    Final Marks of '.$sparent->get_child_stamp_by_id($cur_child).' for term '.$sparent->get_term_stamp_by_id($_GET['termID']).'
                </h3>
                <div class="card-body  px-lg-5 pt-0 mt-md-5">
                <form>
                    <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th scope="col">Subject</th>
                          <th scope="col">Professor</th>
                          <th scope="col">Final Grade</th>
                        </tr>
                      </thead>
                      <tbody>';

//Note: a warning message is already displayed in the index, so for now an ugly die is enough

    $grades = $sparent->get_final_term_marks_by_studentID($cur_child,$_GET['termID']);
    if ($grades) {
        foreach ($grades as $grade) {
            // t.Name, Mark, u.Surname
            $content .= '<tr>
        <th>' . $grade['Name'] . '</th>
        <td>' . $grade['Surname'] . '</td>
        <td>' . $grade['Mark'] . '</td>
      </tr>';
        }
        $content .= ' </tbody>
                      </table>
                      </div>
                      </form>
                      </div>
                      </div>
                      </ul>';
    }else{
        $content = '
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
			  <h4 class="alert-heading">Warning!</h4>
			  <p><strong>No final Term marks available for the selected Term</strong></p>
			</div>';
    }
}
$page->setContent($content);
$site->render();
?>
