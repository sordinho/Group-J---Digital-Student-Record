<?php
require_once("../../config.php");

$teacher = new teacher();

$site = new csite();
initialize_site($site);
$page = new cpage("");
$site->setPage($page);

if (!$teacher->is_logged()) {
	header("location: /error.php?errorID=19");
	exit();
}

$content = '<div class="card">
                <h2 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">
                    Uploaded Material
                </h2>
                <div class="card-body  px-lg-5 pt-0 mt-md-5">
                <form>
                <div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">Filename</th>
        <th scope="col">Description</th>
        <th scope="col">Class</th>
        <th scope="col">Subject</th>
        <th scope="col">Date</th>
      </tr>
    </thead>
    <tbody>';

$uploadedMaterial = $teacher->get_uploaded_material();
foreach ($uploadedMaterial as $i => $row) {
	$content .= '<tr>
        <td>' . $row['FileName'] . '</td>
        <td>' . $row['Description'] . '</td>
        <td>' . $row['YearClassID'] ." ". $row['Section'] .'</td>
        <td>' . $row['Name'] . '</td>
        <td>' . date("Y-m-d", strtotime($row['Date'])) . '</td>
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