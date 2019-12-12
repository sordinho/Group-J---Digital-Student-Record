<?php


class sparent extends user {

	//private $childs = array();

	public function __construct() {
		parent::__construct();
	}

	//returns the result of the query that selects all the grades of @childID
	public function get_grades($childID) {

		if (!isset($childID)) {
			return array();
		}
		$grades_info = array();
		$conn = $this->connectMySql();
		$stmt = $conn->prepare("SELECT t.Name, Mark, Timestamp, u.Surname 
                FROM  Topic t, MarksRecord M, Teacher Te,User u
                WHERE M.TeacherID = Te.ID AND Te.UserID=u.ID AND t.ID=M.TopicID
                    AND M.StudentID = ?
                ORDER BY Timestamp ");
		$stmt->bind_param('s', $childID);
		$stmt->execute();
		$res = $stmt->get_result();
		if (!$res) {
			return false;
		}
		while ($row = $res->fetch_assoc()) {
			array_push($grades_info, $row);
		}
		return $grades_info;
	}

	// Register the childs in a session
	public function retrieve_and_register_childs() {
		$childs = array();
		$children_info = array();
		$conn = $this->connectMySql();
		$stmt = $conn->prepare("SELECT S.ID AS StudentID, P.ID AS ParentID, S.Name, S.Surname 
            FROM Parent P,Student S
            WHERE P.UserID = ?
            AND P.StudentID = S.ID;");
		//todo: use getter
		$stmt->bind_param('d', $_SESSION['id']);//use getter
		$stmt->execute();
		$res = $stmt->get_result();
		/*while($row = $res->fetch_row()){
			array_push($childs, $row[0]);
		}
		//$_SESSION['childsID'] = $childs;*/
		if (!$res) {
			die("Error in SQL query. Contact admin (#Err: 137)");
		}

		while ($row = $res->fetch_assoc()) {
			//var_dump($row);
			array_push($children_info, $row);
		}
		$_SESSION['childrenInfo'] = $children_info;
		return $res;
	}

	public function get_homeworks($childID) {
		if (!isset($childID)) {
			return array();
		}
		$homework_info = array();
		$conn = $this->connectMySql();
		$stmt = $conn->prepare("SELECT
                                          h.ID AS HomeworkID,
                                          h.Description AS HomeworkDescription,
                                          h.Deadline AS HomeworkDeadline,
                                          t.Name as TopicName
                                        FROM
                                          Student s,
                                          SpecificClass sc,
                                          Homework h,
                                          Topic t
                                        WHERE
                                          s.SpecificClassID = sc.ID AND h.SpecificClassID = s.SpecificClassID AND h.TopicID=t.ID AND s.ID = ?");
		$stmt->bind_param('i', $childID);
		$stmt->execute();
		$res = $stmt->get_result();
		if (!$res) {
			return false;
		}
		while ($row = $res->fetch_assoc()) {
			array_push($homework_info, $row);
		}
		return $homework_info;
	}

	/**
	 * @param int $childID
	 * @param bool $from_date
	 * @param bool $to_date
	 * @return array|bool - array of all absences selected or false on failure
	 */
	public function get_absences_and_delays($childID, $from_date = false, $to_date = false) {
		if (!isset($childID)) return false;

		$conn = $this->connectMySql();
		$is_valid_from = calendar::validate_date($from_date, 'Y-m-d');
		$is_valid_to = calendar::validate_date($to_date, 'Y-m-d');
		//TODO: change this code snippet by avoiding repetition in validate_date
		if ($is_valid_from and $is_valid_to and $from_date < $to_date) {
			/*there are two dates which are not false and in a valid format*/
			$sql = $conn->prepare("    SELECT Date, Late, ExitHour FROM NotPresentRecord WHERE StudentID = ? AND Date >= ? AND Date < ?");
			$sql->bind_param('iss', $childID, $from_date, $to_date);

		} else if ($is_valid_from) {
			/*only from_date is set, the other one is set at false or not in a valid form*/
			$sql = $conn->prepare("    SELECT Date, Late, ExitHour FROM NotPresentRecord WHERE StudentID = ? AND Date >= ?");
			$sql->bind_param('is', $childID, $from_date);

		} else if ($is_valid_to) {
			/*only to_date is set, the other one is set at false or not in a valid form*/
			$sql = $conn->prepare("    SELECT Date, Late, ExitHour FROM NotPresentRecord WHERE StudentID = ? AND Date < ?");
			$sql->bind_param('is', $childID, $to_date);

		} else if ($from_date == false and $to_date == false) {
			/*both dates are set to false or the value has not been inserted*/
			$sql = $conn->prepare("SELECT Date, Late, ExitHour FROM NotPresentRecord WHERE StudentID = ?");
			$sql->bind_param('i', $childID);
		} else {
			/*all the other cases are not valid*/
			return false;

		}
		$absences = array();
		$sql->execute();
		$res = $sql->get_result();
		if (!$res) {
			return false;
		}
		while ($row = $res->fetch_assoc())
			array_push($absences, $row);

		return $absences;
	}


	/**
	 * @param int $childID
	 * @param bool $from_date
	 * @param bool $to_date
	 * @return array|bool - array of all absences selected or false on failure
	 */
	public function get_absences($childID, $from_date = false, $to_date = false) {
		if (!isset($childID)) return false;

		$conn = $this->connectMySql();
		$is_valid_from = calendar::validate_date($from_date, 'Y-m-d');
		$is_valid_to = calendar::validate_date($to_date, 'Y-m-d');
		//TODO: change this code snippet by avoiding repetition in validate_date
		if ($is_valid_from and $is_valid_to and $from_date < $to_date) {
			/*there are two dates which are not false and in a valid format*/
			$sql = $conn->prepare("    SELECT Date FROM NotPresentRecord WHERE StudentID = ? AND ExitHour = 0 AND Date >= ? AND Date < ?");
			$sql->bind_param('iss', $childID, $from_date, $to_date);

		} else if ($is_valid_from) {
			/*only from_date is set, the other one is set at false or not in a valid form*/
			$sql = $conn->prepare("    SELECT Date FROM NotPresentRecord WHERE StudentID = ? AND ExitHour = 0 AND Date >= ?");
			$sql->bind_param('is', $childID, $from_date);

		} else if ($is_valid_to) {
			/*only to_date is set, the other one is set at false or not in a valid form*/
			$sql = $conn->prepare("    SELECT Date FROM NotPresentRecord WHERE StudentID = ? AND ExitHour = 0 AND Date < ?");
			$sql->bind_param('is', $childID, $to_date);

		} else if ($from_date == false and $to_date == false) {
			/*both dates are set to false or the value has not been inserted*/
			$sql = $conn->prepare("SELECT Date FROM NotPresentRecord WHERE StudentID = ? AND ExitHour = 0");
			$sql->bind_param('i', $childID);
		} else {
			/*all the other cases are not valid*/
			return false;

		}
		$absences = array();
		$sql->execute();
		$res = $sql->get_result();
		if (!$res) {
			return false;
		}
		while ($row = $res->fetch_assoc())
			array_push($absences, $row);

		return $absences;
	}

	// Return the announcement as array of array (the last 4 news are returned by default)
	public function get_announcements($last_n = 4) {
		$anouncements = array();
		$conn = $this->connectMySql();
		$stmt = $conn->prepare("SELECT * FROM Communication ORDER BY Timestamp DESC LIMIT ?;");
		$stmt->bind_param('i', $last_n);
		if (!$stmt)
			die("DB Error while parsing communication. Contact admin");
		$stmt->execute();
		$res = $stmt->get_result();
		if (!$res) {
			return false;
		}
		while ($row = $res->fetch_assoc()) {
			array_push($anouncements, $row);
		}
		return $anouncements;
	}

	// Register a child as the current to view and analyze by saving the studentID into the session
	public function set_current_child($childID) {
		// TODO: for security reason should verify that the id is in the children array relative to the parent
		$_SESSION['curChild'] = $childID;
		//$_SESSION['childNames'] = $child_names;

	}

	// Return -1 if no current child was choosen until now, return the studentID of the child otherwise
	public function get_current_child() {
		return isset($_SESSION['curChild']) ? $_SESSION['curChild'] : -1;
	}

	// Return the parent ID from parent table
	public function get_parent_ID() {
		return isset($_SESSION['parentID']) ? $_SESSION['parentID'] : -1;
	}

	// Return the current registered child for the parent
	public function get_children_info() {
		$children[0]["Name"] = "No children";
		$children[0]["Surname"] = "Registered";
		$children[0]["StudentID"] = "-1";
		return isset($_SESSION['childrenInfo']) ? $_SESSION['childrenInfo'] : $children;
	}

	// Override of parent method, also check if the id was sent correctly
	public function is_logged() {
		$cond = parent::is_logged() && $this->get_parent_ID() != -1;
		return $cond;
	}

	public function get_child_stamp_by_id($childID) {
		if (!isset($childID)) {
			return array();
		}
		$conn = $this->connectMySql();
		$stmt = $conn->prepare("SELECT
                                          Name,Surname
                                        FROM
                                          Student s
                                        WHERE
                                          s.ID = ?");
		$stmt->bind_param('i', $childID);
		$stmt->execute();
		$res = $stmt->get_result();
		if (!$res) {
			return false;
		}
		while ($row = $res->fetch_assoc()) {
			$stamp = $row["Name"] . " " . $row["Surname"];
		}
		return $stamp;
	}

	#not sure about the parameter to pass
	public function get_material_info($childID) {
		if (!isset($childID)) {
			return array();
		}

		$material_info = array();
		$conn = $this->connectMySql();
		$stmt = $conn->prepare("    SELECT UCD.FileName AS FileName, UCD.DiskFileName AS DiskFileName, UCD.Description AS Description, Topic.Name AS SubjectName, UCD.Date AS Date
                                            FROM UploadedClassDocuments UCD, Student S, Topic
                                            WHERE UCD.SpecificClassID = S.SpecificClassID 
                                            AND Topic.ID = UCD.SubjectID
                                            AND S.ID = ?");
		$stmt->bind_param('i', $childID);
		$stmt->execute();
		$res = $stmt->get_result();

		if (!$res) {
			return false;
		}
		while ($row = $res->fetch_assoc()) {
			array_push($material_info, $row);
		}
		return $material_info;
	}


	public function get_notes() {
		$conn = $this->connectMySql();
		$notes = array();

		// Get notes
		$query = "SELECT u.Name as teacherName, u.Surname as teacherSurname, Date, Description, s.Name as studentName , s.Surname as studentSurname
						FROM Note n, NoteRecord nr,Student s, Teacher t, User u
						WHERE n.TeacherID=t.ID
						AND nr.NoteID=n.ID
						AND nr.StudentID=s.ID
						AND t.UserID=u.ID
						and s.ID IN (SELECT StudentID FROM parent WHERE ID = ?);";
		$stmt = $conn->prepare($query);

		$stmt->bind_param('i', $this->get_parent_ID());
		$stmt->execute();
		$res = $stmt->get_result();

		if (!$res) {
			return false;
		}
		while ($row = $res->fetch_assoc()) {
			array_push($notes, $row);
		}
		return $notes;
	}
}