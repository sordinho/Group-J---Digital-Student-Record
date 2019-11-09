<?php
require_once("config.php");

// Handle hidden menu and navbar render (note that is related to the user status (loggedin/typeOfUser))
$hidden_menu = "";
$user = new user();
if (!$user->is_logged()){
	$login_out_button= ' <li class="nav-item"><a class="nav-link text-left text-white py-1 px-0"  data-toggle="modal" href="#myModal"><i class="fas fa-sign-out-alt mx-3"></i><i class="fa fa-caret-right d-none position-absolute"></i><span class="text-nowrap mx-2">Log in</span></a></li>';

} else {
	$login_out_button= ' <li class="nav-item"><a class="nav-link text-left text-white py-1 px-0"  href="'. PLATFORM_PATH .'logout.php"><i class="fas fa-sign-out-alt mx-3"></i><i class="fa fa-caret-right d-none position-absolute"></i><span class="text-nowrap mx-2">Log out</span></a></li>';
}
/*
if (is_admin()) {
    //$navbar_edit .= '<li class="nav-item"><a class="nav-link" data-toggle="modal" href="#registerModal"> Register new clerk</a></li>';
    //$navbar_edit .= '<li class="nav-item"><a class="nav-link" data-toggle="modal" href="#newServiceModal"> Register new service</a></li>';
    $hidden_menu .= '
	<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Admin actions</a>
		<div class="dropdown-menu" aria-labelledby="dropdown01">
		<a class="dropdown-item" data-toggle="modal" href="#registerModal">Register new clerk</a>
		<a class="dropdown-item" href="#newServiceModal">Register new service</a>
		</div>
	</li>
';
} elseif (is_logged()) {// if not admin and logged => clerk
    $hidden_menu .= '
	<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Clerk actions</a>
		<div class="dropdown-menu" aria-labelledby="dropdown01">
		<a class="dropdown-item" href="./clerkAction.php?action=nextTicket">Next customer</a>
		</div>
	</li>
';
}*/

print '<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="'.PLATFORM_PATH.'/css/bootstrap.min.css" crossorigin="anonymous">
		<!-- Other resources -->
		<meta charset="UTF-8">
		<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<!--Custom css/jss-->
		<link rel="stylesheet" href="'.PLATFORM_PATH.'/fonts/fontawesome-all.min.css">
		<link rel="stylesheet" href="'.PLATFORM_PATH.'/fonts/font-awesome.min.css">
		<link rel="stylesheet" href="'.PLATFORM_PATH.'/fonts/fontawesome5-overrides.min.css">

		<link href="'.PLATFORM_PATH.'/css/style.css" rel="stylesheet">
		<link href="'.PLATFORM_PATH.'/css/sidebar.css" rel="stylesheet">
		<title>StudentDigitalRecord System</title>
	</head>
	

	<body>
	<ul class="nav flex-column shadow d-flex sidebar mobile-hid">
		<li class="nav-item logo-holder">
			<div class="text-center text-white logo py-4 mx-4"><a class="text-white text-decoration-none" id="title" href="#"><strong>Sidebar</strong></a><a class="text-white float-right" id="sidebarToggleHolder" href="#"><i class="fas fa-bars" id="sidebarToggle"></i></a></div>
		</li>
		<li class="nav-item"><a class="nav-link active text-left text-white py-1 px-0" href="#"><i class="fas fa-tachometer-alt mx-3"></i><span class="text-nowrap mx-2">Dashboard</span></a></li>
		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="#"><i class="fas fa-user mx-3"></i><span class="text-nowrap mx-2">User profile</span></a></li>
		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="#"><i class="far fa-life-ring mx-3"></i><span class="text-nowrap mx-2">Support tickets</span></a></li>
		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="#"><i class="fas fa-archive mx-3"></i><span class="text-nowrap mx-2">Archive</span></a></li>
		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="#"><i class="fas fa-chart-bar mx-3"></i><span class="text-nowrap mx-2">Statistics</span></a></li>
		<li class="nav-item dropdown"><a class="dropdown-toggle nav-link text-left text-white py-1 px-0 position-relative" data-toggle="dropdown" aria-expanded="false" href="#"><i class="fas fa-sliders-h mx-3"></i><span class="text-nowrap mx-2">Settings</span><i class="fas fa-caret-down float-none float-lg-right fa-sm"></i></a>
			<div class="dropdown-menu border-0 animated fadeIn" role="menu">
			<a class="dropdown-item text-white" role="presentation" href="#"><span>Change password</span></a>
			<a class="dropdown-item text-white" role="presentation" href="#"><span>Change email</span></a>
			<a class="dropdown-item text-white" role="presentation" href="#"><span>More</span></a></div>
		</li>'. 
		$login_out_button.
	'</ul> 
';
/* Render the 2 modal view: Login and Register */
echo '<!-- Modal Login -->
		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-body" style="padding:40px 50px;">
						<form role="form" method="POST" action="' . PLATFORM_PATH . '/index.php">
						<div class="form-group">
							<label for="front_office"><span class="glyphicon glyphicon-user"></span> Email</label>
							<input type="text" class="form-control" name="username" id="username" placeholder="Enter email">
						</div>
						<div class="form-group">
							<label for="password"><span class="glyphicon glyphicon-eye-open"></span> Password</label>
							<input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
						</div>
						<div class="checkbox">
							<label><input type="checkbox" value="" checked>Remember me</label>
						</div>
							<button type="submit" class="btn btn-success btn-block"><span class="glyphicon glyphicon-off"></span> Login</button>
						</form>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
						<!--<p>Not a member? <a href="#">Sign Up</a></p>-->
						<p>Forgot <a href="#">Password?</a></p>
					</div>
				</div>
			</div>
		</div> 
		';
echo '<!-- Modal for registration(signup) -->
	<div class="modal fade" id="registerModal" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-body" style="padding:40px 50px;">
					<form method="POST" action="./register.php">
					<div class="form-group">
						<label for="rfront_office"><span class="glyphicon glyphicon-user"></span> front_office:</label>
						<input type="text" class="form-control" name="front_office" id="rfront_office" placeholder="Your email">
					</div>
					<div class="form-group">
						<label for="rpassword"><span class="glyphicon glyphicon-eye-open"></span> Password</label>
						<input type="password" class="form-control" name="password" id="rpassword" placeholder="Enter password">
					</div>
					<div class="checkbox">
						<label><input type="checkbox" name = "tos" value="yes" checked>Privacy consense</label>
					</div>
						<button type="submit" class="btn btn-success btn-block"><span class="glyphicon glyphicon-off"></span>Register</button>
					</form>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
				</div>
			</div>
		</div>
	</div>';
?>
