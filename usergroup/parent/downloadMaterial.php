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
                    Download material for ' . $sparent->get_child_stamp_by_id($cur_child) . '
                </h3>
                <div class="card-body  px-lg-5 pt-0 mt-md-5">
                <form>
                    <table class="table table-striped">
                      <thead>
                        <tr>     
                          <th scope="col">Downloadable Material</th>
                          <th scope="col">Description</th>
                          <th scope="col">Subject</th>
                          <th scope="col">Date</th>
                        </tr>
                      </thead>
                      <tbody>';

    $grades = $sparent->get_material_info($cur_child);
    if ($grades) {
        foreach ($grades as $i => $row) {
            $content .= ' <tr>
                            <td><button type="button" class="btn btn-warning text-white"><i class="fas fa-file-download  pr-4"></i>' . $row['FileName'] . '</button></td>
                            <td>' . $row['Description'] . '</td>
                            <td>' . $row['SubjectName'] . '</td>
                            <td>' . date("Y-m-d", strtotime($row['Date'])) . '</td>
                          </tr>';
        }
        $content .= ' </tbody>
                      </table>
                      </form>
                      </div>
                      </div>
                      </ul>';
    }

    if ($handle = opendir('../../files/')) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." and $entry != "..") {
                $content .= "<a href=\"download.php?file=" . $entry . "\">$entry</a><br>";
            }
        }
        closedir($handle);
    }
}

$page->setContent($content);
$site->render();