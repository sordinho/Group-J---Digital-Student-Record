<?php


require_once("../../config.php");


$site = new csite();
initialize_site($site);
$page = new cpage("");
$site->setPage($page);
$sparent = new sparent();
$cur_child = $sparent->get_current_child();

if (!$sparent->is_logged()) {
    header("location: /error.php?errorID=19");
    exit();
}

// TD check if child was selected

# Declare the div where the calendar will be injected, include dependency and custom js script to handle calendar
$content = '';

$timetable = $sparent->get_timetable($cur_child);
if (!$timetable){
    $content .= '<div class="alert alert-danger" role="alert"> The timetable is still not available </div>';
} else {

    $table = '';
    for ($hour = 0; $hour < calendar::get_hours_per_school_day(); $hour++) {
        $table .= '<tr>';
        $table .= '<th scope="row">' . ($hour + 1) . '</th>';
        for ($day = 0; $day < calendar::get_days_per_school_week(); $day++) {
            $table .= '<td>' . $timetable[$day][$hour] . '</td>';
        }
        $table .= '</tr>';
    }

    $content = '
            <ul class="list-group">
            <div class="card">
                <h3 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">
                    Timetable of ' . $sparent->get_child_stamp_by_id($cur_child) . '
                </h3>
                <div class="card-body  px-lg-5 pt-0 mt-md-5">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col"> </th>
                              <th scope="col">Monday</th>
                              <th scope="col">Tuesday</th>
                              <th scope="col">Wednesday</th>
                              <th scope="col">Thursday</th>
                              <th scope="col">Friday</th>
                            </tr>
                          </thead>
                          <tbody>
                          ' . $table . '
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </ul>';
}

$page->setContent($content);
$site->render();