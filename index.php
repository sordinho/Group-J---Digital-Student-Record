<?php
include 'config.php';

$site = new csite();
initialize_site($site);
$page = new cpage("Home");
$site->setPage($page);

$content = "<center><h2>Test2</h2></center>";
$content.='<a href="parent/parent.php">parentPage</a>';

$page->setContent($content);
$site->render();

if(!isset($_SESSION['id']) && isset($_POST['username'])) {
    echo "You just tried login";
    $usr = new user;
    $usr->storeFormValues( $_POST );
    
    if( $usr->userLogin() ) {
        $usergroup = $usr->get_usergroup();
        $url = "";
        switch($usergroup){
            case "Teacher":
                $url .= "/usergroup/teacher/index.php";
                break;
            case "TODO":
                $url .= "/TODO.php";
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
    echo "Authenticated?".$usr->is_logged();
    echo "\nUsergroup: ".$usr->get_usergroup();
    echo "\nUsername: ".$usr->get_username();

}


?>
