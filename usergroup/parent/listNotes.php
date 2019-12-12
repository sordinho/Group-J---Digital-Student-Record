<?php

require_once("../../config.php");


$site = new csite();
initialize_site($site);
$page = new cpage("");
$site->setPage($page);
$sparent = new sparent();

if (!$sparent->is_logged()) {
	header("location: /error.php?errorID=19");
	exit();
}

$content = '<ul class="list-group">
            <div class="card">
                <h3 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">Notes</h3>
                <div class="card-body  px-lg-5 pt-0 mt-md-5">
                <form>
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th scope="col">Student</th>
                          <th scope="col">Description</th>
                          <th scope="col">Date</th>
                          <th scope="col">Professor</th>
                        </tr>
                      </thead>
                      <tbody>';

$notes = $sparent->get_notes();
if ($notes) {
	foreach ($notes as $i => $row) {
		$content .= '<tr>
        <th scope="row">' . $row['studentName'] . ' ' . $row['studentSurname'] . '</th>
        <td>' . $row['Description'] . '</td>
        <td>' . $row['Date'] . '</td>
        <td>' . $row['teacherName'] . ' ' . $row['teacherSurname'] . '</td>
      </tr>';
	}
}

$content .= ' </tbody>
                      </table>
                      </form>
                      </div>
                      </div>
                      </ul>';
$page->setContent($content);
$site->render();
?>
