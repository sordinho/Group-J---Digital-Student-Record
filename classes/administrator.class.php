<?php

require_once 'user.class.php';

class administrator extends user {

	public function __construct() {
		parent::__construct();
	}

	/**
	 *  Function used by administrator to register new users. Password is randomly generated.
	 *
	 * @param $mail
	 * @param $name
	 * @param $surname
	 * @param $usergroup
	 * @return bool
	 * @throws Exception
	 */

	function register_new_user($user_info) {
		$mysqli = $this->connectMySQL();
		$password = $this->random_str(10);
		if ($password == "")
			return false;

		//'salt' => custom_function_for_salt(), //eventually define a function to generate a  salt
		// default is 10, better have a little more security
        //echo "Name: ".$user_info['name']." Surname: ".$user_info['surname']." Email: ".$user_info['email']." Usergroup: ".$user_info['usergroup']." Password: ".$user_info['password'];
		$options = ['cost' => 12];
		$hashed_password = password_hash($password, PASSWORD_DEFAULT, $options);

		$sql = "INSERT INTO User (Name, Surname, Email, Password, UserGroup) VALUES (?, ?, ?, ?, ?)";
		$query = $mysqli->prepare($sql);
		$query->bind_param("sssss", $user_info['name'],$user_info['surname'],$user_info['email'],$hashed_password,$user_info['usergroup']);

		$res = $query->execute();
		if (!$res) {
			printf("Error message: %s\n", $mysqli->error);
			return false;
		} else {
			$message = "You are now officially registered in the Digital Student Record System.\nYour login data will follow.\nUsername: " . $user_info['email'] . "\nPassword: " . $password . "\nFor your security, please delete this message ASAP.";
			$message .= "\nBest Regards\nThe school administration.";
			$message = wordwrap($message, 70, "\n");
			//TODO remove comment on server
			/*if (!mail($user_info['user_email'], "Access Credentials (DSR)", $message))
				return false;*/
			$query->close();
			$mysqli->close();
			return true;
		}
	}

}