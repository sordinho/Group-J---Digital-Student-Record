<?php
require_once("config.php");
// Handle hidden menu and navbar render (note that is related to the user status (loggedin/typeOfUser))

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
		<link rel="stylesheet" href="' . PLATFORM_PATH . '/css/bootstrap.min.css" crossorigin="anonymous">
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
		<link rel="stylesheet" href="' . PLATFORM_PATH . '/fonts/fontawesome-all.min.css">
		<link rel="stylesheet" href="' . PLATFORM_PATH . '/fonts/font-awesome.min.css">
		<link rel="stylesheet" href="' . PLATFORM_PATH . '/fonts/fontawesome5-overrides.min.css">

		<link rel="stylesheet" href="' . PLATFORM_PATH . '/css/timeline.css">
		<link href="' . PLATFORM_PATH . '/css/calendar_style.css" rel="stylesheet">
		<link href="' . PLATFORM_PATH . '/css/style.css" rel="stylesheet">
		<link href="' . PLATFORM_PATH . '/css/sidebar.css" rel="stylesheet">
		<title>StudentDigitalRecord System</title>
	</head>
	
	<body>
	
	'. $content .'
	
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
							<input type="email" class="form-control" name="username" id="username" placeholder="Enter email">
						</div>
						<div class="form-group">
							<label for="password"><span class="glyphicon glyphicon-eye-open"></span> Password</label>
							<input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
						</div>
						
						
						
						<div class="text-center">
                            <div class="btn-group btn-group-toggle m-3" data-toggle="buttons">
                                <label class="btn btn-outline-secondary">
                                    <input type="radio" name="usergroup" value="parent">Parent
                                </label>
                                <label class="btn btn-outline-secondary">
                                    <input type="radio" name="usergroup" value="teacher">Teacher
                                </label>
                                <label class="btn btn-outline-secondary">
                                    <input type="radio" name="usergroup" value="officer">Officer
                                </label>
                                <label class="btn btn-outline-secondary">
                                    <input type="radio" name="usergroup" value="admin">Admin
                                </label>
                            </div>
                                
                            <div class="checkbox">
                                <label><input type="checkbox" value="" checked>Remember me</label>
                            </div>
						</div>

						
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
