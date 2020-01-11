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

define("NUM_SLOT", 3);
define("WEEK", 3);

if (!isset($_POST['submit'])) {

    if (isset($_GET['operation_result'])) {
        switch ($_GET['operation_result']) {
            case 1:
                $content .= '
                <div class="alert alert-success" role="alert">
                    Meeting successfully booked. <a href="bookMeeting.php" class="alert-link">Keep booking meetings</a> or <a href="../parent/index.php" class="alert-link">back to your homepage.</a>
                </div>';
                break;
            case 0:
                $content .= '
                <div class="alert alert-danger" role="alert">
                    Error. <a href="bookMeeting.php" class="alert-link">Retry </a> or <a href="../parent/index.php" class="alert-link">back to your homepage.</a>
                </div>';
                break;
            default:
                $content .= '
                <div class="alert alert-dark" role="alert">
                    Operation not allowed.
                </div>';
        }
    } else {
        $teacher = $sparent->get_teacher_availability();
        $content .=<<<OUT
<div class="card">
<h3 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">Book a meeting</h3>
<div class="card-body  px-lg-5 pt-0 mt-md-5">
<div class="table-responsive">
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th scope="col">Professor</th>
      <th scope="col">Topic</th>
      <th scope="col">Date</th>
      <th scope="col">Hour</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>

OUT;
        for ($week = 0; $week < WEEK; $week++) {
            foreach ($teacher as $meeting) {
                $slot = $sparent->get_future_reservations_by_teacher_availability_id($meeting['TeacherAvailabilityID']);
                for ($i=0; $i < NUM_SLOT; $i++) {
                    if ($i !== $slot[$i]['TimeSlot']) {
                        $teacherID = $meeting['TeacherID'];
                        $teacherName = $meeting['TeacherName'];
                        $teacherSurname = $meeting['TeacherSurname'];
                        $topicName = $meeting['TopicName'];
                        $day = calendar::from_num_to_dow($meeting['DayOfWeek']);
                        $hour = $meeting['HourSlot'];
                        $hourSlot = $meeting['HourSlot']+8;
//                        $timeSlot = $i*20;
                        $timeSlot = 60/NUM_SLOT*$i;
                        if ($timeSlot==0) $timeSlot = "00";
                        $d = 'next '.$day;
                        $date = new DateTime();
                        $date = $date->modify($d);
                        $w = '+'.($week*7).' days';
                        $date = $date->modify($w);
                        $date = $date->format('Y-m-d');
                        if (!calendar::is_holiday($date)) {
                            $content .=<<<OUT
<tr>
  <form method="POST">
      <th scope="row"><input type="hidden" value="$teacherID" name="teacherID">$teacherName $teacherSurname</th>
      <td>$topicName</td>
      <td>$day, <input type="hidden" value="$date" name="date">$date</td>
      <td><input type="hidden" value="$hour" name="hourSlot">$hourSlot:<input type="hidden" value="$i" name="timeSlot">$timeSlot</td>
      <td style="text-align: end"><button class="btn btn-success" type="submit" name="submit" value="bookMeeting">Confirm</button></td>
  </form>
</tr>
OUT;
                        }

                    }
                }
            }
        }


        $content .=<<<OUT
  </tbody>
</table>
</div>

</div>
</div>
OUT;
    }

} else {
    if ($sparent->book_meeting($_POST['teacherID'], $_POST['date'], $_POST['hourSlot'], $_POST['timeSlot'])) {
        header("Location: bookMeeting.php?operation_result=1");
        die();
    } else {
        header("Location: bookMeeting.php?operation_result=0");
        die();
    }

}


$page->setContent($content);
$site->render();