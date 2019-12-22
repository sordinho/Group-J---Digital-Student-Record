<?php
require_once("config.php");

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

?>
