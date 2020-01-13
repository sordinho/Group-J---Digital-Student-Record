<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("");
$site->setPage($page);
$teacher = new teacher();

if (!$teacher->is_logged()) {
    $teacher->get_error(19);
    exit();
}

define("NUM_SLOT", 3);
define("WEEK", 3);


$availability = $teacher->get_availability();
$content .=<<<OUT
<div class="card">
<h3 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">Meeting hours</h3>
<div class="card-body  px-lg-5 pt-0 mt-md-5">
<div class="table-responsive">
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Hour</th>
      <th scope="col">Booked</th>
    </tr>
  </thead>
  <tbody>

OUT;
        foreach($availability as $meetingHour){

            $day = calendar::from_num_to_dow($meetingHour['DayOfWeek']);
            $dateNextDay = date("Y-m-d",strtotime("next $day"));

            $booked = $teacher->get_booked_meetings($dateNextDay,$meetingHour['ID']);
            $hour = $meetingHour['HourSlot']+8;
            $content.="<tr>
<td>$day</td>
<td>$hour:00</td>
<td>$booked for $dateNextDay</td>
</tr>";
        }


$content .=<<<OUT
  </tbody>
</table>
</div>

</div>
</div>
OUT;





$page->setContent($content);
$site->render();