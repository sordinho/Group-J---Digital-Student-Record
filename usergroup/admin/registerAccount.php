<?php

require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Account Registration");
$site->setPage($page);

$administrator = new administrator();

if (!$administrator->is_logged() || !$administrator->is_admin()) {
	$content = '
    <div class="alert alert-warning" role="warning">
        You are not authorized. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
    </div> ';
	$content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
	$page->setContent($content);
	$site->render();
	exit();
}else {

    if (!empty($_POST)) {
        $userInfo = array();
        $userInfo['name'] = $_POST['user_first_name'];
        $userInfo['surname'] = $_POST['user_last_name'];
        $userInfo['email'] = $_POST['user_email'];
        $userInfo['usergroup'] = $_POST['usergroup'];
        $userInfo['password'] = $_POST['user_password'];

        if ($administrator->register_new_user($userInfo)) {
            $content = '
			<div class="alert alert-success" role="warning">
			User enrolled <a href="./registerAccount.php" class="alert-link">just click here!</a>
    		</div> ';
            $content .= "<meta http-equiv='refresh' content='2'/>";
        } else {
            $content = '
			<div class="alert alert-danger" role="warning">
			There was a problem enrollin the user <a href="./registerAccount.php" class="alert-link">just click here!</a>
    		</div> ';
            $content .= "<meta http-equiv='refresh' content='2' />";
        }
    } else {
        $content = "

<div class=\"card\">
    <h5 class=\"card-header info-color white-text text-center py-4\">
        <strong>Enter Master Data of User</strong>
    </h5>
    
    <div class=\"card-body px-lg-5 pt-0\">
        <p class=\"card-body info-color white-text text-center py-4\">Insert Data</p>
        <form action='registerAccount.php' method='post'>
        
          <div class=\"form-row\">
                <div class=\"form-group col-md-6\">
                  <label for=\"inputName\">First Name</label>
                  <input type=\"text\" id=\"materialRegisterFormFirstName\" name=\"user_first_name\" class=\"form-control\" placeholder=\"Insert Name\">
                </div>
                <div class=\"form-group col-md-6\">
                  <label for=\"inputSurname\">Last Name</label>
                  <input type=\"text\" id=\"materialRegisterFormLastName\" name=\"user_last_name\" class=\"form-control\"  placeholder=\"Insert Last Name\">
                </div>
          </div>
          
          <div class=\"form-row\">
          
          	<div class=\"form-group col-md-6\">
            	<label for=\"inputFC\">Email</label>
            	<input type=\"text\" class=\"form-control\" id=\"inputFC\" name=\"user_email\" placeholder=\"Insert Email\">
          	</div>
          	
            <div class=\"form-group col-md-6\">
                <label for=\"inputFC\">Usergroup</label>
            
                <div class=\"input-group mb-3\">
                  <select class=\"custom-select\" id=\"inputGroupSelect01\" name='usergroup'>
                    <option selected>Choose...</option>
                    <option value=\"teacher\">Teacher</option>
                    <option value=\"officer\">Officer</option>
                    <option value=\"principal\">Principal</option>
                  </select>
                </div>
            </div>
          </div>
          
          <div class=\"form-row\">
            <div class=\"form-group col-md-6\">
            
                  <label for=\"inputPassword5\">Password</label>
                  <input type=\"password\" id=\"inputPassword5\" class=\"form-control\" aria-describedby=\"passwordHelpBlock\" name=\"user_password\">
            </div>
          </div>
          
          <button type=\"submit\" class=\"btn btn-primary\">Confirm</button>
        </form>
    </div>
</div>";
    }
    $page->setContent($content);
    $site->render();
}