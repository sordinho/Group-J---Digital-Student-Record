<?php
//error_reporting(E_ALL | E_DEPRECATED | E_STRICT);
/*
 *
 *
 */
require_once(__DIR__ . "/../site_config.php");

class user {
// NOTA: per il momento i setters settano sia $_SESSION che gli attributi di classe.
	protected $login_iduser = null;
	protected $username = null;
	protected $password = null;
	protected $usergroup = null;
	protected $name = null;
	protected $surname = null;
	protected $base_url = null; // cosa dobbiamo mettere? usergroup/parent/ ad esempio?

	public function __construct($data = array()) {
		if (isset($data['username'])) $this->username = stripslashes(strip_tags($data['username']));
		if (isset($data['password'])) $this->password = stripslashes(strip_tags($data['password']));
		//In real not usefull if not creating with Users($_SESSION)
//        if (isset($data['usergroup'])) $this->usergroup = stripslashes(strip_tags($data['usergroup'])); // todo remove if we want to make login same for all users
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

	public function storeFormValues($params) {
		//Memorizzo i parametri
		$this->__construct($params);
	}

	/** Get data for a given user to log in (administrative officer, teacher, parents, admin)
	 *
	 * Service Id is set only for clerk
	 *
	 * @param $post_data
	 * @return userinfo []
	 */
	public function getUserData($id) {
		$userinfo = [];
		return $userinfo;
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

	/** Verify username and password for a user to consent log in (can be different "types" of users)
	 *
	 * @param $post_data : array containing username and password
	 * @return bool
	 */
	function user_login($post_data) {
		$username = $post_data["username"];
		$password = $post_data["password"];
		$retrievedUsergroup = "";
		$success = false;

		$mysqli = new mysqli(DBAddr, DBUser, DBPassword, DBName);
		if ($mysqli->connect_errno) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			return $success;
		}

		//TODO: extend with name and surname (maybe save that in an array as user_info[] ?)
		// Here using prepared statement to avoid SQLi
		$query = $mysqli->prepare("SELECT ID, Name, Surname, Password,UserGroup FROM User WHERE Email = ?");
		$query->bind_param('s', $username);
		$res = $query->execute();
		if (!$res) {
			printf("Error message: %s\n", $mysqli->error);
			return $success;
		}

		$query->store_result();
		$query->bind_result($id, $name, $surname, $pass, $retrievedUsergroup);
		// In case of success there should be just 1 user for a given (username is also a primary key for its table)
		if ($query->num_rows != 1) {
			return $success;
		}
		$query->fetch();
		if (password_verify($password, $pass)) {
			// If here login was successful (hash was verified)
			$success = true;
			$this->set_logged($id);
			$this->set_usergroup($retrievedUsergroup);
			$this->set_username($username);
			$this->set_name($name);
			$this->set_surname($surname);
			$this->base_url = "/usergroup/" . $retrievedUsergroup . "/";
			$this->set_base_url($this->base_url);

			// Get specific ID for teacher, parent ...
			switch ($retrievedUsergroup) {
				case "parent":
					$queryID = $mysqli->prepare("SELECT ID FROM Parent where UserID = ?");
					break;
				case "teacher":
					$queryID = $mysqli->prepare("SELECT ID FROM Teacher where UserID = ?");
					break;
				default:
					return false;
			}
			$queryID->bind_param('i', $userID);

			$result = $queryID->execute();
			if (!$result) {
				printf("Error message: %s\n", $mysqli->error);
				return false;
			}
			$queryID->store_result();
			$queryID->bind_result($specificID);
			// In case of success there should be just 1 user for a given (username is also a primary key for its table)
			if ($query->num_rows != 1) {
				return false;
			}
			$query->fetch();
			$this->setSpecificID($specificID, $retrievedUsergroup);
		}
		$query->close();
		$mysqli->close();

		return $success;
	}

	/**
	 * This function sets the specific ID (parentID, teacherID ...) given the user ID and the usergroup
	 * @param $specificID : general user ID from user table
	 * @param $usergroup
	 */
	private function set_specific_ID($specificID, $usergroup) {
		switch ($usergroup) {
			case "parent":
				$_SESSION['parentID'] = $specificID;
				break;
			case "teacher":
				$_SESSION['teacherID'] = $specificID;
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
		return isset($_SESSION['id']) ? $_SESSION['id'] : '';
	}

	public function get_base_url() {
		return isset($_SESSION['base_url']) ? $_SESSION['base_url'] : '';

	}

	/* Error handling */
	// Redirect to error.php (Error handler)
	public function get_error($id, $noref = null) {
		$html = "<meta http-equiv='refresh' content='0; url=" . $this->base_url . "/error.php?message=$id' />";
		if (!empty($noref)) {
			$html = "<meta http-equiv='refresh' content='0; url=" . $this->base_url . "/error.php?message=$id&noref=1' />";
		}
		print $html;
		//echo "url=".$this->base_url."/error.php?message=$id";
		exit();
	}

	/*  Return the logout link */
	public function get_link_logout() {
		if ($this->is_logged()) {
			return '<a href="' . $this->Urls['logout_page'] . '" class="logout">Logout</a>';
		}
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

	// Verify username syntax
	public function is_username($username) {
		$regex = '/^[a-z0-9\.\-_]{3,30}$/i';
		return preg_match($regex, $username);
	}

	// Verify email syntax (TRUE if ok)
	public function is_email($email) {
		$regex = '/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)+$/';
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