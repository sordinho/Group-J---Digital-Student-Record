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
	function register_new_user($mail, $name, $surname, $usergroup) {
		$mysqli = $this->connectMySQL();
		$password = $this->random_str(10);

		//'salt' => custom_function_for_salt(), //eventually define a function to generate a  salt
		// default is 10, better have a little more security
		$options = ['cost' => 12];
		$hashed_password = password_hash($password, PASSWORD_DEFAULT, $options);

		$sql = "INSERT INTO User (Name, Surname, Email, Password, UserGroup) VALUES (?, ?, ?, ?, ?)";
		$query = $mysqli->prepare($sql);
		$query->bind_param("sssss", $name, $surname, $mail, $hashed_password, $usergroup);

		$res = $query->execute();
		if (!$res) {
			printf("Error message: %s\n", $mysqli->error);
			return false;
		} else {
			$query->close();
			$mysqli->close();
			return true;
		}
	}

}