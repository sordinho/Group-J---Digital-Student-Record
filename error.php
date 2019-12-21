<?php

require_once("config.php");

/* Array per la gestione degli errori */
$messages = array(
	1 => 'Username field is mandatory.',
	2 => 'Email field is mandatory.',
	3 => 'Password field is mandatory.',
	4 => 'Password mismatch.',
	5 => 'Username field contains invalid characters. Only letters, numbers and the following symbols are allowed . _ -.',
	6 => 'Insert a valid email.',
	7 => 'Password too short.<br>It should be al least 5 characters.',
	8 => 'This username is already registered.',
	9 => 'This email is already registered.',
	10 => 'Registration successfully completed.<br>',
	11 => 'Login error.',
	12 => 'Login successful.',
	13 => 'Logout successful.',
	14 => 'You need to be logged in for viewing this page.',
	15 => 'Fill all the fields and insert at least one telephone number.',
	17 => 'Activation successful!',
	18 => 'Attempt to modify parameters.<br>That\'s not funny.',
	19 => 'You\'re not authorized to view this resource.',
    20 => 'Please select a role.',
    21 => 'You should select at least one field.',
    22 => 'Failed at registering a new record.',
    23 => 'Text area is mandatory. <br> It must be filled.',
    24 => 'The selected class has no students.',
    25 => 'Operation not allowed.',
    26 => 'Usergroup selection error.',
    27 => 'Cannot retrieve any usergroup.'
);
if(isset($_GET['errorID'])){
	$message_script = $_GET['errorID'];
}
$key = intval($message_script);
if(array_key_exists($key, $messages)){
	$site = new csite();
	initialize_site($site);
	$page = new cpage("ERROR");
	$site->setPage($page);
	$content = '
    <div class="alert alert-danger" role="warning">
        '.$messages[$key].' If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
    </div> ';
	$content .= "<meta http-equiv='refresh' content='5; url=" . PLATFORM_PATH . "' />";
	$page->setContent($content);
	$site->render();
	exit();
}
if(isset($_SERVER['HTTP_REFERER']) && !isset($_GET['usergroup_redirect'])){//Se usergroup_redirect è settato dopo l'errore non si torna alla pagina di referer
	$referer = $_SERVER['HTTP_REFERER'];
}
else{
	$referer = "./index.php";
}
die( "<meta http-equiv='refresh' content='2; url=$referer' />");
?>