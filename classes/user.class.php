<?php
//error_reporting(E_ALL | E_DEPRECATED | E_STRICT);
/*
 *
 *
 */
require_once(__DIR__ . "/../site_config.php");

class user {

	public function __construct() {
	}

	// protected and not private so that every inheriting class can access this method
	protected function connectMySQL() {
		$mysqli = new mysqli(DBAddr, DBUser, DBPassword, DBName);
		/* check connection */
		if ($mysqli->connect_errno) {
			printf("Connect failed: %s\n", $mysqli->connect_errno);
			exit();
		}
		return $mysqli;
	}

	/**
	 * @param $mail , $password, $name, $surname, $student
	 * @return bool
	 */
	function register($mail, $password, $name, $surname, $student) {
		//TODO: eventually modify for other types of registration - this is thought for parents
		$success = false;
		/*
		// TODO: eventually edit with has_permission() (related to admin capabilities to add clerk)
		if (!is_admin()) {
			//die("You are already registered and logged in");
			return $success;
		}*/
		$mysqli = new mysqli(DBAddr, DBUser, DBPassword, DBName);
		if ($mysqli->connect_errno) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			return $success;
		}

		$options = [
			//'salt' => custom_function_for_salt(), //eventually define a function to generate a  salt
			'cost' => 12 // default is 10, better have a little more security
		];
		$hashed_password = password_hash($password, PASSWORD_DEFAULT, $options);
		// In a real scenario it should be a nice practice to generate an activation code and let the user confirm that value (ex. with a link)
		//$activation_code = rand(100, 999).rand(100,999).rand(100,999);
		//TODO: modify because parent now has esternal KEY userID with pwd and email
		$sql = "INSERT INTO parent (ID, Name, Surname, Email,  Password, StudentID) VALUES (0, ?, ?, ?, ?, ?)";
		$query = $mysqli->prepare($sql);
		$query->bind_param("ssssi", $name, $surname, $mail, $hashed_password, $student);
		//TODO: handle ID value
		$res = $query->execute();
		if (!$res) {
			printf("Error message: %s\n", $mysqli->error);
			return $success;
		} else {
			$query->close();
			$mysqli->close();
			$mail_enc = urlencode($mail);
			$url = PLATFORM_PATH;
			$url .= "register.php?front_office=" . $mail_enc;
			die("<meta http-equiv='refresh' content='1; url=$url' />");
		}
	}

	function get_user_group_table_name($usergroup) {
		$table_name = false;
		switch ($usergroup) {
			case "parent":
				$table_name = "Parent";
				break;
			case "teacher":
				$table_name = "Teacher";
				break;
			case "officer":
				$table_name = "Officer";
				break;
			default:
				$table_name = false;
		}

		return $table_name;
	}

	/** Verify username and password for a user to consent log in (can be different "types" of users)
	 *
	 * @param $post_data : array containing username and password
	 * @return bool
	 */
	function user_login($post_data) {
		$username = $post_data["username"];
		$password = $post_data["password"];
		$retrievedUsergroup = "";

		$mysqli = new mysqli(DBAddr, DBUser, DBPassword, DBName);
		if ($mysqli->connect_errno) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			return false;
		}

		//TODO: extend with name and surname (maybe save that in an array as user_info[] ?)
		// Here using prepared statement to avoid SQLi
		$query = $mysqli->prepare("SELECT ID, Name, Surname, Password, UserGroup FROM User WHERE Email = ?");
		$query->bind_param('s', $username);
		$res = $query->execute();
		if (!$res) {
			printf("Error message: %s\n", $mysqli->error);
			return false;
		}

		$query->store_result();
		$query->bind_result($id, $name, $surname, $pass, $retrievedUsergroup);
		// In case of success there should be just 1 user for a given (username is also a primary key for its table)
		if ($query->num_rows != 1) {
			return false;
		}
		$query->fetch();
		if (password_verify($password, $pass)) {
			// If here login was successful (hash was verified)
			$this->set_logged($id);
			$this->set_usergroup($retrievedUsergroup);
			$this->set_username($username);
			$this->set_name($name);
			$this->set_surname($surname);
			$base_url = "/usergroup/" . $retrievedUsergroup . "/";
			$this->set_base_url($base_url);

			// Get specific ID for teacher, parent ...

			$user_group_table = $this->get_user_group_table_name($retrievedUsergroup);
			$specificID = -1;
			if ($user_group_table != false) {
				$queryID = $mysqli->prepare("SELECT ID FROM " . $user_group_table . " WHERE UserID = ?");
				$queryID->bind_param('i', $id);

				$result = $queryID->execute();
				if (!$result) {
					printf("Error message: %s\n", $mysqli->error);
					return false;
				}
				$queryID->store_result();
				$queryID->bind_result($specificID);
				// In case of success there should be just 1 *USER* for a given (username is also a primary key for its table)
				if ($queryID->num_rows < 1) {
					return false;
				}
				$queryID->fetch();
				$this->set_specific_ID(intval($specificID), $retrievedUsergroup);
			} else {
				return false;
			}
		} else
			return false;
		$query->close();
		$mysqli->close();

		return true;
	}
	/**
	 * This function sets the specific ID (parentID, teacherID ...) given the user ID and the usergroup
	 * @param $specificID : general user ID from user table
	 * @param $usergroup
	 */
	// BTW was easier to write just a specificID and use it in combination with usergroup
	private function set_specific_ID($specificID, $usergroup) {
		switch ($usergroup) {
			case "parent":
				$_SESSION['parentID'] = $specificID;
				break;
			case "teacher":
				$_SESSION['teacherID'] = $specificID;
				break;
			case "officer":
				$_SESSION['officerID'] = $specificID;
				break;
			default:
				break;
		}
	}

	/***********************************
	 *            SETTERS
	 ***********************************/

	// set login
	protected function set_logged($id_user) {
		$_SESSION['id'] = $id_user;
		$this->login_iduser = $id_user;
		return;
	}

	//Memorizza nelle sessioni lo username
	protected function set_username($username) {
		$_SESSION['username'] = $username;
		return;
	}

	protected function set_usergroup($usergroup) {
		$_SESSION['usergroup'] = $usergroup;
		$this->usergroup = $usergroup;
		return;
	}

	// Save name for gui?
	protected function set_name($name) {
		$_SESSION['name'] = ucfirst($name);
		$this->name = $name;
		return;
	}

	// Save surname for GUI
	protected function set_surname($surname) {
		$_SESSION['surname'] = $surname;
		$this->surname = $surname;
	}

	protected function set_base_url($baseUrl) {
		$_SESSION['base_url'] = $baseUrl;
		return;
	}

	/***********************************
	 *             GETTERS
	 ***********************************/

	// verifica login
	public function is_logged() {
		return isset($_SESSION['id']);
	}

	//Restituisce la mail memorizzata nelle sessioni
	public function get_username() {
		return isset($_SESSION['username']) ? $_SESSION['username'] : '';
	}

	public function get_name() {
		return isset($_SESSION['name']) ? $_SESSION['name'] : '';
	}

	public function get_surname() {
		return isset($_SESSION['surname']) ? $_SESSION['surname'] : '';
	}

	public function get_usergroup() {
		return isset($_SESSION['usergroup']) ? $_SESSION['usergroup'] : '';
	}

	public function get_id() {
		return isset($_SESSION['id']) ? $_SESSION['id'] : -1;
	}

	public function get_base_url() {
		return isset($_SESSION['base_url']) ? $_SESSION['base_url'] : '';
	}

	/* Error handling */
	// Redirect to error.php (Error handler)
	public function get_error($id, $noref = null) {
		$html = "<meta http-equiv='refresh' content='0; url=" . PLATFORM_PATH . "/error.php?message=$id' />";
		if (!empty($noref)) {
			$html = "<meta http-equiv='refresh' content='0; url=" . PLATFORM_PATH . "/error.php?message=$id&noref=1' />";
		}
		print $html;
		//echo "url=".$this->base_url."/error.php?message=$id";
		exit();
	}

	/*  Return the logout link */
	public function get_link_logout() {
		if ($this->is_logged())
			return '<a href="' . $this->Urls['logout_page'] . '" class="logout">Logout</a>';
		else
			return '';
	}


	/*
	public function sendEmail($to, $subject, $content, $name){
		//Modifico php.ini per far si da utilizzare il protocollo smtp senza autenticazione.
		ini_set('SMTP', 'smtp.mydomain.it');
		ini_set('smtp_port', 25);
		ini_set('sendmail_from', "info@mydomain.it");
		//Controllo che l'email sia sintassicamente corretta.
		if(!$this->is_email($to)) {
			return false;
		}
		$subject = htmlspecialchars($subject);
		// message
		$message = '
		<html>
		<head>
			<title>Grazie '.$name.'</title>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		</head>
		<body>'.
			$content
		.'</body><br><br>
		<small>
			Questa email è stata generata dal sito <a href=http://mydomain.it>colosi.it</a><br>
			Per ogni altra informazione o per l\'eliminazione del proprio account andare sul sito o scrivere a<br>
			info@mydomain.it</small>
		</html>
		';
		// Modifico il Content-type poichè è una mail in formato html.
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		// Aggiungo header per non mandarla in SPAM.
		$headers .= 'To: '.htmlspecialchars($name).' <'.$to.'>' . "\r\n";
		$headers .= 'From: Digital Student Recorder<info@mydomain.it>' . "\r\n";
		// Uso la funzione mail per inviare.
		if(mail($to, $subject, $message, $headers)){
			return true;
		}
		else{
			return false;
		}
	}*/

	// Verify email syntax (TRUE if ok)
	public function is_email($email) {
		$regex = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';
		return preg_match($regex, $email);
	}

	// Verify if constraints on password strength are satisfied
	public function is_secure_password($password) {
		if (strlen($password) >= 5) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

?>