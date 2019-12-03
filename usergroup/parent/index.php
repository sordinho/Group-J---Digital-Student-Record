<?php

require_once("../../config.php");


$site = new csite();
initialize_site($site);
$page = new cpage("");
$site->setPage($page);
$sparent = new sparent();

if (!$sparent->is_logged() ) {
	header("location: /error.php?errorID=19");
	exit();
}


if ($_GET['action'] == "switchChild") {
	$new_childID = intval($_GET["childID"]);
	$sparent->set_current_child($new_childID);
	$content = '
			<div class="col justify-content-lg-center">
				<div class="article-clean">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-grow text-success" style="width: 10rem; height: 10rem;" role="status">
                        	<span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
				<div class="text-center"><button type="button" class="btn btn-outline-success">
					Student is switching.<br> Loading... If you are in a hurry 
					<a href="./index.php" class="alert-link">click here!</a>
				</button></div>
			</div>';
	$content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . $_SESSION["base_url"] . "' />";
} else {
	// If no child was choosen show an alert
	if ($sparent->get_current_child() == -1) {
		$hidden_warning = '
			<div class="alert alert-warning alert-dismissible fade show" role="alert">
			  <h4 class="alert-heading">Hello!</h4>
			  <p>Please, <strong>select a student</strong> you want to operate on from the sidebar.</p>
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
			  <hr>
			  <p class="mb-0">After selected a student, you can access all the functionalities in the sidebar.</p>
			</div>';
	}
	else {
		$content_after_selected_child = '';
	}
	$content = '
				
		
		<div class="card">
			<h2 style="background-color:rgba(108,108,108,0.9);color:white" class="card-header info-color white-text text-center py-4">
				Logged as: parent - '.$sparent->get_name().' '.$sparent->get_surname().'
			</h2>
			
			<div class="row justify-content-lg-center card-body">
				<div class="card-body p-lg-4">
					<div class="card" style="width: 15rem;">
					  <div class="card-body">
						<h4 class="card-title">Where to start?</h4>
						<h5 class="card-subtitle mb-2 text-muted">Find a functionality</h5>
						<p class="card-text">On the sidebar you can perform different actions.</p>
					  </div>
					</div>
				</div>
				
				<div class="card-body p-lg-4">
					<div class="card" style="width: 15rem;">
					  <div class="card-body">
						<h4 class="card-title">Feeling lost?</h4>
						<h5 class="card-subtitle mb-2 text-muted">Choose your role</h5>
						<p class="card-text">Are you sure you selected the right role during log in?</p>
					  </div>
					</div>
				</div>
				
				<div class="card-body p-lg-4">
					<div class="card" style="width: 15rem;">
					  <div class="card-body">
						<h4 class="card-title">In a hurry?</h4>
						<h5 class="card-subtitle mb-2 text-muted">Work on the go</h5>
						<p class="card-text">Feel free to use the system from your smartphone.</p>
					  </div>
					</div>
				</div>
				
				<div class="text pb-4">
					<p>' . $hidden_warning . '</p>
				</div>
			</div>
		</div>';
}

$page->setContent($content);
$site->render();
?>
