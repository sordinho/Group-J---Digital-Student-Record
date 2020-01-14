<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Activate new parents");
$site->setPage($page);
$officer = new officer();

if(!$officer ->is_logged() ){
	header("location: /error.php?errorID=19");
	exit();
}

$num = 1;
$content = "";

if ($_GET["action"] != "activate") {
	$content = '
    <!-- The container  -->
    <div class="container article-clean">
        <div class="wrapper text-center">
            <br/>
            <h1>Batch parent activation process</h1>
            <p class="lead">Click on the button to generate the parent authentication data and send them by mail.<br></p>
            <a class="btn btn-primary" href="./batchActivateAuthentication.php?action=activate"role="button">Parent activation</a>
        </div>
    </div>';
} else {
	// call method to get the current inactive account
	$parents = $officer->get_parents_without_access_credentials();
	if (count($parents) == 0) {
		//td edit this
		$content = <<<OUT
<div class="alert alert-danger" role="alert">
 There are no parents without credentials.<a href="index.php" class="alert-link">Back to your homepage.</a>
</div>
OUT;
	} else {
		$succes = false;
		foreach ($parents as $parent) {
			$pwd = $officer->generate_and_register_password($parent['ID']);
			if ($pwd != "") {
				// A valid password was generated, send it by mail
				$message = "You are now officially registered in the Digital Student Record System.\nYour login data will follow.\nUsername: " . $parent['Email'] . "\nPassword: " . $pwd . "\nFor your security, please delete this message ASAP.";
				$message .= "\nBest Regards\nThe school administration.";
				$message = wordwrap($message, 70, "\n");
				// try to send mail
				if (!defined('MAIL_DISABLE') || MAIL_DISABLE == FALSE){
					$succes = mail($parent['Email'], "Access Credentials (DSR)", $message);
				}
				else{// if skip mail, let's assume it was sent 
					$succes = true;
				}
			}
		}
		if ($succes) {
			$content .= '
                <div class="alert alert-success" role="alert">
                    Parent credentials successfully generated and sent. <a href="index.php" class="alert-link">Back to your homepage.</a>
                </div>
                ';
		} else {
			$content = '
                <div class="alert alert-danger" role="alert">
                    Error in sending parent\'s credentials. <a href="batchActivateAuthentication.php" class="alert-link">Retry </a> or <a href="index.php" class="alert-link">back to your homepage.</a>
                </div>';
		}
	}
}
$page->setContent($content);
$site->render();
