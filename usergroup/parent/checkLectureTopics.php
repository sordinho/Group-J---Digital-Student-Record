<?php

require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("");
$site->setPage($page);

$sparent = new sparent();

if (!$sparent->is_logged()) {
	$sparent->get_error(19);
	exit();
}

$content = '<div class="card">
                <h2 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">
                    List of Lectures
                </h2>
                <div class="card-body  px-lg-5 pt-0 mt-md-5">
                <form>
                <div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">Teacher</th>
        <th scope="col">Subject</th>
        <th scope="col">Description</th>
        <th scope="col">Class</th>
        <th scope="col">Date</th>
      </tr>
    </thead>
    <tbody>';

$lectureTopics = $sparent->get_lecture_topics($sparent->get_current_child());

foreach ($lectureTopics as $i => $row) {
	$content .= '<tr>
        <td>' . $row['TeacherName'] . ' ' . $row['TeacherSurname'] . '</td>
        <td>' . $row['TopicName'] . '</td>
        <td>' . $row['Description'] . '</td>
        <td>' . $row['YearClass'] . $row['Section'] . '</td>
        <td>' . $row['Date'] . '</td>
      </tr>';
}


$content .= '
    </tbody>
  </table>
  </div>
  </form>
  </div>';


$page->setContent($content);
$site->render();
?>
