<?php
include 'config.php';

$site = new csite();
initialize_site($site);
$page = new cpage("DIGITAL RECORD SYSTEM");
$site->setPage($page);


$content = "";

if (isset($_POST['usergroup'])) {
	$usr = new user();
	if ($usr->select_usergroup($_POST['usergroup'])) {
		$url = PLATFORM_PATH.$usr->get_base_url();
		header("location: $url");
	} else {
		$usr->get_error(26);
	}
}

if (isset($_SESSION['id']) && isset($_SESSION['username']) && !isset($_SESSION['usergroup']) && $_GET['select_usergroup']==1) {
	$usr = new user();
	$usergroup = $usr->retrieve_usergroups($usr->get_username());
	foreach ($usergroup as $ug) {
		$icon = "";
		switch ($ug) {
			case 'teacher':
				$icon = "fas fa-briefcase";
				break;
			case 'parent':
				$icon = "fas fa-child";
				break;
			case 'officer':
				$icon = "fas fa-user-cog";
				break;
			case 'admin':
				$icon = "fas fa-tools";
				break;
		}
		$roles .= "
		<div class='col col-sm col-md'>
		<form method='POST' action='index.php'>
				<button class='card-role rounded' style='border: none; background: none' type='submit' name='usergroup' value='$ug'>
					  <i class='$icon fa-2x'></i>
					  <div class='container-role'>
						<h5><b>$ug</b></h5>
					  </div>
				</button>
		</form>
		</div>
		";
	}
	$content .= "
				<div class=\"card text-center\" id='usergroup-selection'>
				<div class=\"card-header\" style=\"background-color:rgba(108,108,108,0.9);color:white\">
					<p class='h3'>Select a role for the login</p>
				</div>
				<div class=\"card-body row\">
					$roles
				</div>
			</div>
	";

} elseif (!isset($_SESSION['id']) && isset($_POST['username'])) {
	/*if (!isset($_POST['usergroup'])) {
		$url = 'location: '.PLATFORM_PATH.'/error.php?errorID=20';
		header($url);
		exit();
	}*/

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

	$result = $usr->user_login($post_data);

	switch ($result) {
		case 1:
			$url = $usr->get_base_url() . "index.php";
			if ($usr->get_usergroup() == 'parent') {
				$sparent = new sparent();
				$sparent->retrieve_and_register_childs();
			}
			$content .= "<meta http-equiv='refresh' content='1; url=" . PLATFORM_PATH . $url . "' />";
			break;

		/*case -1:
			$usr->get_error(11);
			break;*/

		case 2:
			$url = PLATFORM_PATH."/index.php?select_usergroup=1";
			$content .= "<meta http-equiv='refresh' content='1; url=" . $url . "' />";
//			header("location: $url");
			break;

		default:
			$usr->get_error(11);
			break;
	}
} else {
	$usr = new user();
	if (isset($_SESSION['id']) && isset($_SESSION['username']) && !isset($_SESSION['usergroup'])) {
		$content .= "<meta http-equiv='refresh' content='0; url=" . PLATFORM_PATH . "/index.php?select_usergroup=1' />";
	} elseif($usr->is_logged() && isset($_SESSION['usergroup'])){
		$content .= "<meta http-equiv='refresh' content='0; url=" . PLATFORM_PATH . $usr->get_base_url() . "' />";
	}
}
$page->setContent($content);
$site->render();


?>
