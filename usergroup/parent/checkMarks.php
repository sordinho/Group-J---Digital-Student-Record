<?php

require_once("../../config.php");


$site = new csite();
initialize_site($site);
$page = new cpage("Marks");
$site->setPage($page);
$sparent = new sparent();

if (!$sparent->is_logged() ) {
	header("location: /error.php?errorID=19");
	exit();
}
# If user is correctly authenticated:
$cur_child = $sparent->get_current_child();
$content = '<ul class="list-group">
            <div class="card">
                <h5 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">
                    <strong>'.$sparent->get_child_stamp_by_id($cur_child).'</strong>
                </h5>
                <div class="card-body  px-lg-5 pt-0 mt-md-5">
                <form>
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
if ($cur_child == -1) {
  die('<div class="alert alert-danger" role="alert">Please, select a student</div>');
}

$grades = $sparent->get_grades($cur_child);
if ($grades) {
  foreach ($grades as $i => $row) {
    //TODO: CHANGE WHEN get_grades return correct data
    //NOT t.Name, Mark, Timestamp, u.Surname (u.surname is useless)
    $content .= '<tr>
        <th scope="row">' . $row['Timestamp'] . '</th>
        <td>' . $row['Mark'] . '</td>
        <td>' . $row['Name'] . '</td>
        <td>' . $row['Surname'] . '</td>
      </tr>';
  }
  $content .= '
    </tbody>
  </table>
  </form>
  </div>
  </div>
  </ul>';
}


$page->setContent($content);
$site->render();
?>
