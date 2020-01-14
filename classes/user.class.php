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
	 * Get DB table name from a givn usergroup
	 * @param $usergroup : usergroup name [parent, teacher, officer, ...]
	 * @return bool|string
	 */
	function get_user_group_table_name($usergroup) {
		$usergroup_valid_array = ["Teacher", "Officer", "Parent"];
		$c_usergroup = ucfirst($usergroup);
		return in_array($c_usergroup, $usergroup_valid_array) ? $c_usergroup : false;

	}

	/** Verify username and password for a user to consent log in (can be different "types" of users)
	 *
	 * @param $post_data : array containing username and password
	 * @return bool
	 */
	function user_login($post_data) {
		$return = -1;
		$username = $post_data["username"];
		$password = $post_data["password"];

		$mysqli = new mysqli(DBAddr, DBUser, DBPassword, DBName);
		if ($mysqli->connect_errno) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			$return = -1;
		}

		//TD: extend with name and surname (maybe save that in an array as user_info[] ?)
		// Here using prepared statement to avoid SQLi
		$query = $mysqli->prepare("SELECT ID, Name, Surname, Password, UserGroup FROM User WHERE Email = ?");
		$query->bind_param('s', $username);
		$res = $query->execute();
		if (!$res) {
			printf("Error message: %s\n", $mysqli->error);
			$return = -1;
		}

		$query->store_result();
		$query->bind_result($id, $name, $surname, $pass, $usergroup);
		// In case of success there should be just 1 user for a given (username is also a primary key for its table)
		if ($query->num_rows == -1) {
			$return = -1;
		}
		$query->fetch();
		if (password_verify($password, $pass)) {

			/*
			 * the ID is always set
			 * in case of multiple usergroups it will be overwritten
			 */
			$this->set_logged($id);
			$this->set_username($username);
			$this->set_name($name);
			$this->set_surname($surname);
			$num_rows = $query->num_rows;

			if ($num_rows == 1) {
				if ($this->set_session_usergroup($usergroup, $id, $mysqli)) {
					$return = 1; // successfully login + one usergroup
				}
			} elseif ($num_rows > 1) {
				$return = 2; // successfully login + multiple usergroups
			} else {
				$return = -1;
			}

		} else
			$return = -1;

		$query->close();
		$mysqli->close();

		return $return;
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

	public function set_session_usergroup($retrievedUsergroup, $userID, $mysqli) {

		// If here login was successful (hash was verified)
		$this->set_usergroup($retrievedUsergroup);

		$base_url = "/usergroup/" . $retrievedUsergroup . "/";
		$this->set_base_url($base_url);
		// Get specific ID for teacher, parent ...
		if ($retrievedUsergroup == 'admin') {
			$this->set_admin();
		} else {
			$user_group_table = $this->get_user_group_table_name($retrievedUsergroup);
			$specificID = -1;
			if ($user_group_table != false) {
				/** @noinspection SqlResolve */
				$queryID = $mysqli->prepare("SELECT ID FROM " . $user_group_table . " WHERE UserID = ?");
				$queryID->bind_param('i', $userID);

				$result = $queryID->execute();
				if (!$result) {
					printf("Error message: %s\n", $mysqli->error);
					$queryID->close();
					return false;
				}
				$queryID->store_result();
				$queryID->bind_result($specificID);
				// In case of success there should be just 1 *USER* for a given (username is also a primary key for its table)
				if ($queryID->num_rows < 1) {
					$queryID->close();
					return false;
				}
				$queryID->fetch();
				$this->set_specific_ID(intval($specificID), $retrievedUsergroup);
			} else {
				return false;
			}
		}

		return true;
	}

	public function select_usergroup($usergroup) {
		$return = false;
		$mysqli = $this->connectMySQL();

		/*
		 * $_SESSION['ID'] overwrite with the specified usergroup
		 */
		if ($userID = $this->retrieve_user_id_by_usergroup($this->get_username(), $usergroup)) {
			if ($this->set_session_usergroup($usergroup, $userID, $mysqli)) {
				$return = true;
			}
		}

		$mysqli->close();
		return $return;
	}

	public function retrieve_usergroups($username) {
		$usergroup = array();
		$mysqli = $this->connectMySQL();
		/* check connection */
		if ($mysqli->connect_errno) {
			printf("Connect failed: %s\n", $mysqli->connect_errno);
			$mysqli->close();
			return array();
		}

		$stmt = $mysqli->prepare("SELECT UserGroup FROM User WHERE Email = ?");
		$stmt->bind_param('s', $username);
		if (!$stmt->execute()) {
			$stmt->close();
			$mysqli->close();
			return array();
		}

		$res = $stmt->get_result();
		if ($res->num_rows > 1) {
			while ($row = $res->fetch_object()) {
				$usergroup[] = $row->UserGroup;
			}
		}

		$stmt->close();
		$mysqli->close();
		return $usergroup;

	}

	public function retrieve_user_id_by_usergroup($email, $usergroup) {
		$conn = $this->connectMySQL();
		$stmt = $conn->prepare("SELECT ID FROM User WHERE Email = ? AND UserGroup = ?");
		$stmt->bind_param('ss', $email, $usergroup);
		if (!$stmt->execute()) {
			$stmt->close();
			$conn->close();
			return -1;
		}

		$res = $stmt->get_result();
		if ($res->num_rows == 1) {
			$row = $res->fetch_object();
			$stmt->close();
			$conn->close();
			return $row->ID;
		}

		return -1;
	}

	/***********************************
	 *            SETTERS
	 ***********************************/

	/**
	 * Set user id in session. => user is logged in
	 * @param $id_user : user_id this is ID from User table
	 */
	protected function set_logged($id_user) {
		$_SESSION['id'] = $id_user;
		return;
	}

	/**
	 * Set a user as administrator in session
	 */
	protected function set_admin() {
		$_SESSION['admin'] = true;
	}

	/**
	 * Save username in session
	 * @param $username
	 */
	protected function set_username($username) {
		$_SESSION['username'] = $username;
		return;
	}

	/**
	 * Save usergroup in session
	 * @param $usergroup
	 */
	protected function set_usergroup($usergroup) {
		$_SESSION['usergroup'] = $usergroup;
		return;
	}

	/**
	 * Save first name of the user in session
	 * @param $name
	 */
	protected function set_name($name) {
		$_SESSION['name'] = ucfirst($name);
		return;
	}

	/**
	 * Save last name of the user in session
	 * @param $surname
	 */
	protected function set_surname($surname) {
		$_SESSION['surname'] = $surname;
	}

	/**
	 * Save in session the base of the url for a user
	 * @param $baseUrl : Ex: "/usergroup/teacher/"
	 */
	protected function set_base_url($baseUrl) {
		$_SESSION['base_url'] = $baseUrl;
		return;
	}

	/***********************************
	 *             GETTERS
	 ***********************************/


	/**
	 * Check if a user is logged in by checking if set ID in session
	 * @return bool
	 */
	public function is_logged() {
		return isset($_SESSION['id']);
	}


	/**
	 * Get username from session
	 * @return mixed|string
	 */
	public function get_username() {
		return isset($_SESSION['username']) ? $_SESSION['username'] : '';
	}

	/**
	 * Get first name from session
	 * @return mixed|string
	 */
	public function get_name() {
		return isset($_SESSION['name']) ? $_SESSION['name'] : '';
	}

	/**
	 * Get last name from session
	 * @return mixed|string
	 */
	public function get_surname() {
		return isset($_SESSION['surname']) ? $_SESSION['surname'] : '';
	}

	/**
	 * Get usergroup from session
	 * @return mixed|string
	 */
	public function get_usergroup() {
		return isset($_SESSION['usergroup']) ? $_SESSION['usergroup'] : '';
	}

	/**
	 * Get ID from session
	 * @return int|mixed
	 */
	public function get_id() {
		return isset($_SESSION['id']) ? $_SESSION['id'] : -1;
	}

	/**
	 * Get base url from session
	 * @return mixed|string
	 */
	public function get_base_url() {
		return isset($_SESSION['base_url']) ? $_SESSION['base_url'] : '';
	}

	/* Error handling */
	// Redirect to error.php (Error handler)
	public function get_error($id, $noref = null) {
		$html = "<meta http-equiv='refresh' content='0; url=" . PLATFORM_PATH . "/error.php?errorID=$id' />";
		if (!empty($noref)) {
			$html = "<meta http-equiv='refresh' content='0; url=" . PLATFORM_PATH . "/error.php?errorID=$id&noref=1' />";
		}
		print $html;
		//debug "url=".$this->base_url."/error.php?message=$id";
		exit();
	}

	/**
	 * Get a logout link
	 * @return string
	 */
	public function get_link_logout() {
		if ($this->is_logged()) {
			return '<a href="' . $this->Urls['logout_page'] . '" class="logout">Logout</a>';
		} else {
			return '';
		}
	}


	/**
	 * Generate a random string, using a cryptographically secure
	 * pseudorandom number generator (random_int)
	 *
	 * For PHP 7, random_int is a PHP core function
	 *
	 * @param int $length How many characters do we want?
	 * @param string $keyspace A string of all possible characters
	 *                         to select from
	 * @return string
	 * @throws Exception
	 */
	public function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
		$str = '';
		$max = mb_strlen($keyspace, '8bit') - 1;
		if ($max < 1) {
			throw new Exception('$keyspace must be at least two characters long');
		}
		for ($i = 0; $i < $length; ++$i) {
			$str .= $keyspace[random_int(0, $max)];
		}
		return $str;
	}

	/**
	 * Check if an email is a valid one according to the regex
	 * @param $email
	 * @return false|int
	 */
	public function is_email($email) {
		$regex = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';
		return preg_match($regex, $email);
	}

	/**
	 * Check if a password satisfy a length constraint
	 * @param $password
	 * @return bool
	 */
	public function is_secure_password($password) {
		if (strlen($password) >= 5) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Check validity of ITALIAN fiscal code
	 * @param $fcode
	 * @return false|int
	 */
	public function check_fiscal_code($fcode) {
		$regex = '/^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$/';
		return preg_match($regex, $fcode);
	}


	public function get_class_stamp_by_id($classID) {
		$conn = $this->connectMySQL();

		$res = $conn->query("SELECT YearClassID, Section FROM SpecificClass WHERE ID=$classID");
		if ($res->num_rows <= 0) {
			return array();
		}
		$IDs = array();
		for ($i = 0; $i < $res->num_rows; $i++) {
			$row = $res->fetch_assoc();
			$stamp = $row["YearClassID"] . "°" . $row["Section"];
			array_push($IDs, $row);
		}
		$res->close();
		return $stamp;
	}


}

?>