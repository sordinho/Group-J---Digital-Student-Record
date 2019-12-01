<?php

require_once 'user.class.php';
/* A signle admin is assumed for now (no getter, no check og get_id on is_logged) */

class administrator extends user {

	public function __construct() {
		parent::__construct();
	}

	/**
	 *  Function used by administrator to register new users. Password is randomly generated.
	 *
	 * @param $user_first_name
	 * @param $user_last_name
	 * @param $user_email
	 * @param $usergroup
	 * @param $fcode
	 * @return bool
	 * @throws Exception
	 */

	function register_new_user($user_first_name,$user_last_name,$user_email,$usergroup,$fcode) {
		if($user_first_name==null || $user_last_name==null || $user_email==null || $usergroup==null || fcode == null)
			return false;
		$mysqli = $this->connectMySQL();
		$password = $this->random_str(10);
        //TODO modify
		//$password = 'frontoffice1';
		if ($password == "")
			return false;

		//'salt' => custom_function_for_salt(), //eventually define a function to generate a  salt
		// default is 10, better have a little more security
        //echo "Name: ".$user_first_name." Surname: ".$user_last_name." Email: ".$user_email." Usergroup: ".$usergroup." Password: ".$password." Fcode: ".$fcode;
		$options = ['cost' => 12];
		$hashed_password = password_hash($password, PASSWORD_DEFAULT, $options);

		$sql = "INSERT INTO User (Name, Surname, Email, Password, UserGroup) VALUES (?, ?, ?, ?, ?)";
		$query = $mysqli->prepare($sql);
		$query->bind_param("sssss", $user_first_name, $user_last_name, $user_email, $hashed_password, $usergroup);

		$res = $query->execute();
		if (!$res) {
			printf("Error message: %s\n", $mysqli->error);

            echo "Error in inserting the new User";
			return false;
		}

		$stmt=$mysqli->prepare("SELECT ID FROM User WHERE Email=? AND UserGroup=?");
		$stmt->bind_param('ss',$user_email,$usergroup);

		//TODO: first retrieve ID from user table of the inserted user
		//echo $selectID;
		$IDinsertedUser=-1;
		if ($stmt->execute()) {
		    $res=$stmt->get_result();
            $row = $res->fetch_assoc();
			$IDinsertedUser = $row['ID'];
			//echo "ID after Insert: ".$IDinsertedUser;
		} else {
		    echo "Unable to retrieve User ID";
		    return false;
        }
        $tempzero=0;
		//TODO before sending email with credentials, insert data in officer/teacher tables.
		switch ($usergroup) {
			case "teacher":
				$queryInsert = 'INSERT INTO Teacher (MeetingHourID,UserID,FiscalCode) VALUES (?,?,?)';
                $queryInsertSpecificTable = $mysqli->prepare($queryInsert);
                echo "ID User: ".$IDinsertedUser." Fcode: ".$fcode;
                $queryInsertSpecificTable->bind_param("iis", $tempzero,$IDinsertedUser,$fcode);
				break;
			case "officer":
				$queryInsert = 'INSERT INTO Officer (UserID,FiscalCode) VALUES (?,?)';
                $queryInsertSpecificTable = $mysqli->prepare($queryInsert);
                $queryInsertSpecificTable->bind_param("is", $IDinsertedUser,$fcode);
				break;
			default:
                $queryInsert = '';
                $queryInsertSpecificTable = $mysqli->prepare($queryInsert);
				break;
		}


		$result = $queryInsertSpecificTable->execute();
		if (!$result) {
			printf("Error message: %s\n", $mysqli->error);

            echo "Unable to add data to the specific user Table in the DB";
			return false;
		}
		
		$message = "You are now officially registered in the Digital Student Record System.\nYour login data will follow.\nUsername: " . $user_email . "\nPassword: " . $password . "\nFor your security, please delete this message ASAP.";
		$message .= "\nBest Regards\nThe school administration.";
		$message = wordwrap($message, 70, "\n");
		//TODO remove comment on server
		/*if (!mail($user_email, "Access Credentials (DSR)", $message))
			return false;*/
		$query->close();
		$mysqli->close();
		return true;

	}

	// Check if user is an admin. Set at login with set_admin()
	public function is_admin() {
		return isset($_SESSION['admin']) ? $_SESSION['admin'] : false ;
	}

	// Override of parent method, just check if was set admin in session
	public function is_logged()
	{
		$cond = parent::is_logged() && $this->is_admin();
		return $cond;
	}
	
}