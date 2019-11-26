<?php
include 'config.php';

$site = new csite();
initialize_site($site);
$page = new cpage("Your Digital Record System");
$site->setPage($page);


$content = "";

if (!isset($_SESSION['id']) && isset($_POST['username'])) {
	$content .= '<div class="article-clean">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-grow text-warning" style="width: 10rem; height: 10rem;" role="status">
                        <span class="sr-only">Loading...</span>
                        </div>
                    </div>
            
                    <div class="text-center"><button type="button" class="btn btn-outline-warning">Loading...</button></div>
                </div>';
	$usr = new user;

	$post_data["username"] = $_POST['username'];
	$post_data["password"] = $_POST['password'];

	//echo $_POST['username'] . $_POST['password'];

	if ($result = $usr->user_login($post_data)) {
		//$url = "/index.php";
		$url = $usr->get_base_url() . "index.php";
		if ($usr->get_usergroup() == 'parent') {
			$sparent = new sparent();
			$sparent->retrieve_and_register_childs();
		}

		$content .= "<meta http-equiv='refresh' content='1; url=" . PLATFORM_PATH . $url . "' />";
	} else {
		$usr->get_error(11);
	}
} else {
	$usr = new user();
	if($usr->is_logged()){
		$content .= "<meta http-equiv='refresh' content='0; url=" . PLATFORM_PATH . $usr->get_base_url() . "' />";
	}
}
$page->setContent($content);
$site->render();


?>
