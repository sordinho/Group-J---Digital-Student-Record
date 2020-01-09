<?php

require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("");
$site->setPage($page);
$teacher = new teacher();

if (!$teacher->is_logged()) {
    header("location: /error.php?errorID=19");
    exit();
}

if (!isset($_POST["date"]) and !isset($_POST["time"])) {
    if (isset($_GET['operation_result'])) {
        $content = "";
        switch ($_GET['operation_result']) {
            case 1:
                $content .= ' 
								<div class="alert alert-success" role="alert">
								    Assignment successfully registered. <a href="addAvailability.php" class="alert-link">Add another Assignment</a> or <a href="index.php" class="alert-link">back to your homepage.</a>
								</div>
							';
                break;
            case 0:
                $content .= '
								<div class="alert alert-danger" role="alert">
								    Error in registering a new assignment. <a href="addAvailability.php" class="alert-link">Retry </a> or <a href="index.php" class="alert-link">back to your homepage.</a>
								</div>
							';

                break;
            default:
                $content .= '
								<div class="alert alert-dark" role="alert">
								    Operation not allowed.
								</div>
							';
        }
    } else {

        $days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday");

        foreach ($days as $day) {
            $value = json_encode($day);
            $day_list .= "<option value='$value'>" . $day . "</option>";
        }

        $content = '
                <div class="card">
					<h2 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">
						Add Parent Meeting
					</h2>
					<div class="card-body  px-lg-5 pt-0 mt-md-5">
                        <form method="POST">
                    
                            <div class="form-group">
                                <label for="appt">Time of the meeting</label>

                                <input type="time" id="time" name="time" class="form-control"
                                       min="08:00" max="14:00" required style="border-radius: 5px;">
                                <br>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Day of the meeting</label>
                                    <select class="custom-select" name="day" id="dayID">
                                        ' . $day_list . '
                                    </select>
                                </div>
                            </div>
                            
                            <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Confirm</button>	
                            
                        </form>
					</div>
				</div>
					';
    }
} else {

    $day = json_decode($_POST['day'], TRUE);

    if ($teacher->add_availability($day, $_POST["time"])) {
        header("Location: addAvailability.php?operation_result=1");
        die();
    } else {
        header("Location: addAvailability.php?operation_result=0");
        die();
    }
}

$page->setContent($content);
$site->render();


