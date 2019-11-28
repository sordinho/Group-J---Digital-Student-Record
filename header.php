<?php
require_once("config.php");
// Handle hidden menu and navbar render (note that is related to the user status (loggedin/typeOfUser))
$hidden_menu = "";
$user = new user();
$ulp = PLATFORM_PATH.$user->get_base_url(); // usergroup link prefix
if (!$user->is_logged()){
	$login_out_button= ' <li class="nav-item"><a class="nav-link text-left text-white py-1 px-0"  data-toggle="modal" href="#myModal"><i class="fas fa-sign-out-alt mx-3"></i><i class="fa fa-caret-right d-none position-absolute"></i><span class="text-nowrap mx-2">Log in</span></a></li>';

} else {
	$login_out_button= ' <li class="nav-item"><a class="nav-link text-left text-white py-1 px-0"  href="'. PLATFORM_PATH .'/logout.php"><i class="fas fa-sign-out-alt mx-3"></i><i class="fa fa-caret-right d-none position-absolute"></i><span class="text-nowrap mx-2">Log out</span></a></li>';
}

// Custom menu definition for each group
switch($_SESSION["usergroup"]){
	case "parent":
		$par = new sparent();
		$children = $par->get_children_info();
		$hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="'.$ulp.'checkMarks.php"><i class="fas fa-bullseye mx-3"></i><span class="text-nowrap mx-2">Check Marks</span></a></li>';
		$hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./checkHomeworks.php"><i class="fas fa-book mx-3"></i><span class="text-nowrap mx-2">Check Homeworks</span></a></li>';
		$hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./checkAttendance.php"><i class="fas fa-user mx-3"></i><span class="text-nowrap mx-2">Check Attendance</span></a></li>';
		$hidden_menu .= '<li class="nav-item dropdown"><a class="dropdown-toggle nav-link text-left text-white py-1 px-0 position-relative" data-toggle="dropdown" aria-expanded="false" href="#"><i class="fas fa-user-graduate mx-3"></i><span class="text-nowrap mx-2">Students</span><i class="fas fa-caret-down float-none float-lg-right fa-sm"></i></a>
		<div class="dropdown-menu border-0 animated fadeIn" role="menu">';
		foreach ($children as $i=> $child) {
			$hidden_menu .= '
			<a class="dropdown-item text-white" role="presentation" href="./index.php?action=switchChild&childID='. $child["StudentID"].'"><span>'. $child["Name"]." ".$child["Surname"].'</span></a>';
		}
		$hidden_menu .= '</div>
		</li>';
	break;
	case "teacher":
        $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./addLecture.php"><i class="fas fa-book-open mx-3"></i><span class="text-nowrap mx-2">Add Lecture</span></a></li>';
        $hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./listLectures.php"><i class="fas fa-bookmark mx-3"></i><span class="text-nowrap mx-2">List Lectures</span></a></li>';
		$hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./insertGrades.php"><i class="fas fa-marker mx-3"></i><span class="text-nowrap mx-2">Assign Grades</span></a></li>';
		$hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./addAssignment.php"><i class="fas fa-user-clock mx-3"></i><span class="text-nowrap mx-2">Add Assignment</span></a></li>';

		break;
	case "officer":
		$hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./batchActivateAuthentication.php"><i class="fas fa-envelope mx-3"></i><span class="text-nowrap mx-2">Parent Activation</span></a></li>';
		// Upload info menu	
		$hidden_menu .= '		<li class="nav-item dropdown"><a class="dropdown-toggle nav-link text-left text-white py-1 px-0 position-relative" data-toggle="dropdown" aria-expanded="false" href="#"><i class="fas fa-user-tie mx-3"></i><span class="text-nowrap mx-2">Upload Parent Info</span><i class="fas fa-caret-down float-none float-lg-right fa-sm"></i></a>
								<div class="dropdown-menu border-0 animated fadeIn" role="menu">
								<a class="dropdown-item text-white" role="presentation" href="./uploadParentCredentials.php"><span>Manual Insert</span></a>
								<a class="dropdown-item text-white" role="presentation" href="./uploadCSVParentCredentials.php"><span>CSV Upload</span></a>';
		// Stundent enrollment, classcomposition and (unused for now) settings
		$hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./studentEnrollment.php"><i class="fas fa-graduation-cap mx-3"></i><span class="text-nowrap mx-2">Enroll Student</span></a></li>';
		$hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./classCompositionModification.php"><i class="fas fa-users mx-3"></i><span class="text-nowrap mx-2">Handle Classes</span></a></li>';
		$hidden_menu .= '		<li class="nav-item dropdown"><a class="dropdown-toggle nav-link text-left text-white py-1 px-0 position-relative" data-toggle="dropdown" aria-expanded="false" href="#"><i class="fas fa-sliders-h mx-3"></i><span class="text-nowrap mx-2">Settings</span><i class="fas fa-caret-down float-none float-lg-right fa-sm"></i></a>
										<div class="dropdown-menu border-0 animated fadeIn" role="menu">
										<a class="dropdown-item text-white" role="presentation" href="#"><span>Change password</span></a>
										<a class="dropdown-item text-white" role="presentation" href="#"><span>Change email</span></a>
										<a class="dropdown-item text-white" role="presentation" href="#"><span>More</span></a></div>
									</li>';
		break;
	case "admin":
		$hidden_menu .= '		<li class="nav-item"><a class="nav-link text-left text-white py-1 px-0" href="./registerAccount.php"><i class="fas fa-user-plus mx-3"></i><span class="text-nowrap mx-2">Register Account</span></a></li>';

		break;

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
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css" crossorigin="anonymous">

		<!-- Other resources -->
		<meta charset="UTF-8">
		<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.min.js"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/js/bootstrap-datetimepicker.min.js"></script>

		<!--Custom css/jss-->
		<link rel="stylesheet" href="'.PLATFORM_PATH.'/fonts/fontawesome-all.min.css">
		<link rel="stylesheet" href="'.PLATFORM_PATH.'/fonts/font-awesome.min.css">
		<link rel="stylesheet" href="'.PLATFORM_PATH.'/fonts/fontawesome5-overrides.min.css">

		<link href="'.PLATFORM_PATH.'/css/calendar_style.css" rel="stylesheet">
		<link href="'.PLATFORM_PATH.'/css/style.css" rel="stylesheet">
		<link href="'.PLATFORM_PATH.'/css/sidebar.css" rel="stylesheet">
		<title>StudentDigitalRecord System</title>
	</head>
	

	<body>
	<ul class="nav flex-column shadow d-flex sidebar mobile-hid">
		<li class="nav-item logo-holder">
			<div class="text-center text-white logo p-2">
				<a class="text-white float-left" id="sidebarToggleHolder" href="#">
					<i class="fas fa-bars" id="sidebarToggle"></i>
				</a>
				<a class="text-white text-decoration-none p-2" id="title" href="#">
					<img src="' . PLATFORM_PATH . '/media/logopoli2.jpg" alt="logopoli" style="width: 100%; object-fit: contain"/>
				</a>

			</div>
		</li>
		'.
		'<li class="nav-item"><a class="nav-link active text-left text-white py-1 px-0" href="./index.php"><i class="fas fa-home mx-3"></i><span class="text-nowrap mx-2">Home</span></a></li>'.
		$hidden_menu.
		$login_out_button.
	'</ul> 
';
/* Render the 2 modal view: Login and Register */
echo '<!-- Modal Login -->
		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<form role="form" method="POST" action="' . PLATFORM_PATH . '/index.php">
				<div class="modal-content">
					<div class="modal-body" style="padding:40px 50px;">
						
						<div class="form-group">
							<label for="front_office"><span class="glyphicon glyphicon-user"></span> Email</label>
							<input type="text" class="form-control" name="username" id="username" placeholder="Enter email">
						</div>
						<div class="form-group">
							<label for="password"><span class="glyphicon glyphicon-eye-open"></span> Password</label>
							<input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
						</div>
						
						<div class="text-center"> <div class="checkbox">
							<label><input type="checkbox" value="" checked>Remember me</label>
						</div></div>

						
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-success btn-block"><span class="glyphicon glyphicon-off"></span> Login</button>
						<button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
					</div>
				</div>
				</form>
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
