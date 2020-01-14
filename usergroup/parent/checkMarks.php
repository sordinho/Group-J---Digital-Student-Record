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
} else {
    $content = '<ul class="list-group">
            <div class="card">
                <h3 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">
                    ' . $sparent->get_child_stamp_by_id($cur_child) . '\'s marks
                </h3>
                <div class="card-body  px-lg-5 pt-0 mt-md-5">
                <form>
                    <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th scope="col">Date</th>
                          <th scope="col">Grade</th>
                          <th scope="col">Subject</th>
                          <th scope="col">Professor</th>
                        </tr>
                      </thead>
                      <tbody>';

//Note: a warning message is already displayed in the index, so for now an ugly die is enough

    $grades = $sparent->get_grades($cur_child);
    if ($grades) {
        foreach ($grades as $i => $row) {
            //TD: CHANGE WHEN get_grades return correct data
            //NOT t.Name, Mark, Timestamp, u.Surname (u.surname is useless)
            $content .= '<tr>
        <th scope="row">' . $row['Timestamp'] . '</th>
        <td>' . $row['Mark'] . '</td>
        <td>' . $row['Name'] . '</td>
        <td>' . $row['Surname'] . '</td>
      </tr>';
        }
        $content .= ' </tbody>
                      </table>
                      </div>
                      </form>
                      </div>
                      </div>
                      </ul>';
    }
}
$page->setContent($content);
$site->render();
?>
