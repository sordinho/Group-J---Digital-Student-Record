<?php

header('Content-Type: application/json');
require_once("../../config.php");

$sparent = new sparent();
if(!$result){
}else{
    echo json_encode(array('status' => 'success','message'=> 'The group has been removed'));
}

if (!$sparent->is_logged() || $sparent->get_parent_ID() == -1) {

    echo json_encode(array('status' => 'error','message'=> 'Not authorized'));
    exit();
}


$homework_info = $sparent->get_homeworks($sparent->get_current_child());

echo json_encode(array($homework_info));

