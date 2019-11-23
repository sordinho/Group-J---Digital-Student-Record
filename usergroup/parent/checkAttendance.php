<?php


require_once("../../config.php");


$site = new csite();
initialize_site($site);
$sparent = new sparent();
$children = $sparent->get_children_info();
$childID = $sparent->get_current_child();
$key = array_search($childID, array_column($children, 'StudentID'));
$page_title = "Attendance of " . $children[$key]['Name'] . " " . $children[$key]['Surname'];
$page = new cpage($page_title);
$site->setPage($page);

if (!$sparent->is_logged() || $sparent->get_parent_ID() == -1) {
    $content = '
    <div class="alert alert-warning" role="warning">
        You are not authorized. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
    </div> ';
    $content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
    $page->setContent($content);
    $site->render();
    exit();
} else {
    $content =<<<OUT
        <table class="table">
          <thead class="thead-dark">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Date</th>
              <th scope="col">Absence</th>
              <th scope="col">Late</th>
              <th scope="col">Early exit</th>
              <th scope="col">Exit hour</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">1</th>
              <td>2019-11-20</td>
              <td>No</td>
              <td>Yes</td>
              <td>Yes</td>
              <td>11:00</td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td>2019-11-21</td>
              <td>Yes</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <th scope="row">3</th>
              <td>2019-11-22</td>
              <td>No</td>
              <td>No</td>
              <td>Yes</td>
              <td>11:00</td>
            </tr>
          </tbody>
        </table>
OUT;

}

$page->setContent($content);
$site->render();