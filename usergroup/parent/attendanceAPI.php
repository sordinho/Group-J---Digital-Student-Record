<?php
/*
    API used to get information about the homework for a given parent and child.
*/
header('Content-Type: application/json');
require_once("../../config.php");

$sparent = new sparent();

if (!$sparent->is_logged() || $sparent->get_current_child() == -1) {

    echo json_encode(array('status' => 'error','message'=> 'Not authorized'));
    exit();
}
//$attendance_info = $sparent->get_absences($sparent->get_current_child());
$attendance_info = $sparent->get_absences_and_delays($sparent->get_current_child());
if ($attendance_info === False) {
    echo json_encode(array('status' => 'error','message'=> 'No absence/Parsing error'));
    exit();
}
# Create custom array that will be used for GUI
# HP: max 10 subject per class (otherwise just add some colors)
$color_list = ["red", "white", "silver", "olive", "navy", "purple", "green", "orange", "blue", "yellow"];
$topic_color_assoc = array();
$custom_array_assign = array();

foreach ($attendance_info as $key => $value) {
    // todo : refactor
    if($value['Late'] == 1 and $value['ExitHour'] == 6)
        $elem["eventName"] = "Late";
    else if( $value['ExitHour'] == 0)
        $elem["eventName"] = "Not present";
    else if($value['Late'] == 1 and $value['ExitHour']>0 and $value['ExitHour']<6)
        $elem['eventName'] = "Late and early exit";
    else if($value['Late'] == 0 and $value['ExitHour']>0 and $value['ExitHour']<6)
        $elem["eventName"] = "Early exit";
    $elem["full_date"] = $value["Date"];
    $elem["calendar"] =  $elem["eventName"];
    # If a color wasn't assigned until now
    if (!array_key_exists($elem["calendar"], $topic_color_assoc)){
        $topic_color_assoc[$elem["calendar"]] = array_pop($color_list);
    }
    $elem["color"] = $topic_color_assoc[$elem["calendar"]];
    array_push($custom_array_assign, $elem);
}
// Encode as json, add the status field and send it through the net!
echo json_encode(array('status' => 'ok','message'=>$custom_array_assign));

