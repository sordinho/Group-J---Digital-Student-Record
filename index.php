<?php
include 'config.php';

$site = new csite();
initialize_site($site);
$page = new cpage("Home");
$site->setPage($page);


$page->setContent($content);
$site->render();

if(!isset($_SESSION['id']) && isset($_POST['username'])) {
    echo "You just tried login";
    $usr = new user;
    $usr->storeFormValues( $_POST );

    $post_data["username"] = $_POST['username'];
    $post_data["password"] = $_POST['password'];

    //echo $_POST['username'] . $_POST['password'];
    
    if($usr->user_login($post_data)) {
        $usergroup = $usr->get_usergroup();
        $url = "/index.php";
        switch($usergroup){
            case "Teacher":
                $url = "/usergroup/teacher/index.php";
                break;
            case "parent":
                $url = "/usergroup/parent/index.php";
                break;
            case "TODO":
                //$url = "/TODO.php";
                break; 
        }
        echo "Success";
        $html = "<meta http-equiv='refresh' content='1; url=".PLATFORM_PATH.$url."' />";
        die($html);
    } else {
        $usr->get_error(11);
    }
} else {
	$usr = new user();
	echo "Authenticated? " . $usr->is_logged();
	echo "<br>Usergroup: " . $usr->get_usergroup();
	echo "<br>Username: " . $usr->get_username();
	echo "<br>Name: " . $usr->get_name();
	echo "<br>Surname: " . $usr->get_surname();
	echo "<br>ID: " . $usr->get_id();
	echo "<br>Base_URL: " . $usr->get_base_url();
	if($usr->is_logged()){
		// TODO reset refresh to 1 sec. Set to 5 for debug print
		$html = "<meta http-equiv='refresh' content='5; url=" . PLATFORM_PATH . $usr->get_base_url() . "' />";
		die($html);
	}
}


?>
