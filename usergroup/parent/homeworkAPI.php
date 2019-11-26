<?php
/*
    API used to get information about the homework for a given parent and child.
*/
header('Content-Type: application/json');
require_once("../../config.php");

$sparent = new sparent();

if (!$sparent->is_logged() || $sparent->get_parent_ID() == -1 || $sparent->get_current_child() == -1) {

    echo json_encode(array('status' => 'error','message'=> 'Not authorized'));
    exit();
}

$homework_info = $sparent->get_homeworks($sparent->get_current_child());
//{"HomeworkID":1,"HomeworkDescription":"desc1","HomeworkDeadline":"2020-01-08","TopicName":"History"}

# Create custom array that will be used for GUI
# HP: max 10 subject per class (otherwise just add some colors)
$color_list = ["red", "white", "silver", "olive", "navy", "purple", "green", "orange", "blue", "yellow"];
$topic_color_assoc = array();
$custom_array_homew = array();
foreach ($homework_info as $key => $value) {
    $elem["eventName"] = $value["HomeworkDescription"];
    $elem["full_date"] = $value["HomeworkDeadline"];
    $elem["calendar"] =  $value["TopicName"];
    # If a color wasnt assigned until now
    if (!array_key_exists($value["TopicName"], $topic_color_assoc)){
        $topic_color_assoc[$value["TopicName"]] = array_pop($color_list);
    }
    $elem["color"] = $topic_color_assoc[$value["TopicName"]];
    array_push($custom_array_homew, $elem);
}
// Encode as json, add the status field and send it through the net!
echo json_encode(array('status' => 'ok','message'=>$custom_array_homew));

