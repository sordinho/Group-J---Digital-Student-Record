<?php

require_once 'user.class.php';
require_once 'calendar.class.php';

class teacher extends user {

	public function __construct() {
		parent::__construct();
	}

	/*
	 * @lectureDescritpion --> string with the description of a single lecture
	 * @topicID            --> Id of the subject
	 * @timestamp          --> date of the lecture "yyyy-mm-dd hh:mm:ss"
	 *
	 * return               true            if successful
	 *                      false           otherwise
	 * */
	public function insert_new_lecture_topic($lectureDescription, $topicID, $timestamp, $classID) {
		//todo : come arriva la data dell'inserzione? UNIX timestamp o già formattata?
		//       come salviamo nel db il timestamp? Al momento sto ipotizzando arrivino
		//       nello stesso formato di actual_date
		//$classID = -1; //
		// actual unix timestamp
		$actual_date = strtotime(date("Y-m-d H:i:s"));
		// given unix timestamp
		$lecture_date = strtotime($timestamp);
		// secondi in una settimana
		if (!calendar::by_the_end_of_the_week($actual_date, $lecture_date))
			return false;
		$conn = $this->connectMySQL();
		$stmt = $conn->prepare("INSERT INTO TopicRecord (TeacherID, Timestamp, Description, TopicID, SpecificClassID) VALUES (?,?,?,?,?);");
		/*
CREATE TABLE `TopicRecord` (
  `ID` int(11) NOT NULL,
  `TeacherID` int(11) NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Description` varchar(512) NOT NULL,
  `TopicID` int(11) NOT NULL,
  `SpecificClassID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
		*/
		if ($stmt == false)
			return false;
		$stmt->bind_param('issii', $_SESSION['teacherID'], $timestamp, $lectureDescription, $topicID, $classID);
		return $stmt->execute();//True || False
	}

	/*
	 * @newDescription     --> string with the new description of a single lecture (already registeret)
	 * @topicID            --> Id of the subject
	 *
	 * return               true            if successful
	 *                      false           otherwise
	 * */
	public function modify_lecture_topic($newDescription, $topicRecordID) {
		if (!isset($topicRecordID) || !isset($newDescription)) {
			return false;
		}

		$conn = $this->connectMySQL();
		$stmt = $conn->prepare("SELECT Timestamp,TeacherID FROM TopicRecord WHERE ID = ?;");
		if (!$stmt)
			return false;
		$stmt->bind_param('i', $topicRecordID);
		$stmt->execute();
		$res = $stmt->get_result();
		if ($res->num_rows <= 0) {
			return false;
		} else {
			$row = $res->fetch_row();

			//modifica entro la fine della settimana
			$actual_date = strtotime(date("Y-m-d H:i:s"));
			$lecture_date = strtotime($row[0]);
			if (!calendar::by_the_end_of_the_week($actual_date, $lecture_date))
				return false;
			if ($row[1] != $_SESSION['teacherID'])
				return false;
			$res->close();
			$stmt = $conn->prepare("UPDATE TopicRecord SET Description=? WHERE ID=?;");
			if (!$stmt)
				return false;
			$stmt->bind_param("si", $newDescription, $topicRecordID);
			return $stmt->execute();
		}
	}

	/*
	 * Get the topics information for which the teacher is currently in charge of
	 *
	 * return               empty            if successful
	 *                      array of array   otherwise
	 * */
	public function get_topics($selectedClass = 0) {
		$topics = array();
		// TODO create TopicTeacherClass table logic scheme TopicTeacherClass(TopicID, TeacherID, SpecificClassID)
		// Write correct query, use AS to define alias with following names (TopicID, TopicName, TopicDescription)
		$conn = $this->connectMySQL();
		//$teacherID = $this->get_teacher_ID();
		$teacherID = $_SESSION['teacherID'];

		// todo manage class selection
		if ($selectedClass) {
			$stmt = $conn->prepare("SELECT ttc.SpecificClassID as ClassID, tc.ID as TopicID, tc.Name as TopicName, 
                                                tc.Description as TopicDescription 
                                            FROM TopicTeacherClass as ttc, Topic as tc, Teacher as t 
                                            WHERE ttc.TeacherID=t.ID and tc.ID=ttc.TopicID and t.ID=? and ttc.SpecificClassID=?");
			$stmt->bind_param('ii', $teacherID, $selectedClass);

		} else {
			$stmt = $conn->prepare("SELECT tc.ID as TopicID, tc.Name as TopicName, tc.Description as TopicDescription 
                                            FROM TopicTeacherClass as ttc, Topic as tc, Teacher as t 
                                            WHERE ttc.TeacherID=t.ID and tc.ID=ttc.TopicID and t.ID=? ");
			$stmt->bind_param('i', $teacherID);
		}
		$stmt->execute();
		$res = $stmt->get_result();
		if ($res->num_rows <= 0) {
			$dummy["TopicID"] = 0;
			$dummy["TopicName"] = "No topic";
			$dummy["TopicDescription"] = "No topic for this teacher";
			array_push($topics, $dummy);
		} else {
			while ($row = $res->fetch_assoc()) {
				array_push($topics, $row);
			}
		}
		return $topics;
	}

	// Return the teacher ID from teacher table
	public function get_teacher_ID() {
		return isset($_SESSION['teacherID']) ? $_SESSION['teacherID'] : -1;
	}


	public function get_assigned_classes_names() {
		$classes = array();
		$conn = $this->connectMySQL();
		$stmt = $conn->prepare("SELECT DISTINCT ttc.SpecificClassID as ClassID, sc.YearClassID as YearClass, sc.Section as Section 
                                      from TopicTeacherClass ttc, SpecificClass as sc 
                                      WHERE sc.ID = ttc.SpecificClassID and TeacherID=?");
		$teacherID = $this->get_teacher_ID();
		$stmt->bind_param('i', $teacherID);
		$stmt->execute();
		$res = $stmt->get_result();
		if ($res->num_rows <= 0) {
			return false;
		} else {
			while ($row = $res->fetch_assoc()) {
				array_push($classes, $row);
			}
		}
		return $classes;

	}

	/*
	 * get ClassID, TopicName, TopicDescription for a given teacherID
	 */

	public function get_assigned_classes() {
		$classes = array();
		$conn = $this->connectMySQL();
		$stmt = $conn->prepare("SELECT ttc.SpecificClassID as ClassID, t.Name as TopicName, t.Description as TopicDescription, sc.YearClassID as YearClass, sc.Section as Section 
                                      from TopicTeacherClass ttc, Topic as t, SpecificClass as sc 
                                      WHERE ttc.TopicID=t.ID and sc.ID = ttc.SpecificClassID and TeacherID=?");
		$teacherID = $this->get_teacher_ID();
		$stmt->bind_param('i', $teacherID);
		$stmt->execute();
		$res = $stmt->get_result();
		if ($res->num_rows <= 0) {
			return false;
		} else {
			while ($row = $res->fetch_assoc()) {
				array_push($classes, $row);
			}
		}
		return $classes;
	}

	/**
	 * Get list of topic records inserted by the teacher
	 */
	public function get_topics_record() {
		$topicRecords = array();
		$conn = $this->connectMySQL();
		$stmt = $conn->prepare("SELECT TopicRecord.Timestamp as TimeStamps, 
									  TopicRecord.Description as TopicDescription, Topic.Name as TopicName, TopicRecord.ID as RecordID
									  FROM TopicRecord, Topic
									  WHERE TopicRecord.TopicID=Topic.ID AND TopicRecord.TeacherID=?");
//		$teacherID = $this->get_teacher_ID();
		$teacherID = $_SESSION['teacherID'];
		$stmt->bind_param('i', $teacherID);
		$stmt->execute();
		$res = $stmt->get_result();
		if ($res->num_rows <= 0) {
			return false;
		} else {
			while ($row = $res->fetch_assoc()) {
				array_push($topicRecords, $row);
			}
		}
		return $topicRecords;
	}

	public function get_daily_absences($date, $specificClassID) {
		$absences = array();
        if (calendar::validate_date($date,"Y-m-d") == false) return $absences;
		$conn = $this->connectMySQL();
		$stmt = $conn->prepare("SELECT StudentID, Late, ExitHour
                                      FROM NotPresentRecord
                                      WHERE Date = ? 
                                        AND SpecificClassID = ?;");
		if (!$stmt) return $absences;
		$stmt->bind_param("si", $date, $specificClassID);
		$stmt->execute();
		$res = $stmt->get_result();
		if ($res > 0) {
			while ($row = $res->fetch_assoc()) {
				array_push($absences, $row);
			}
		}
		return $absences;
	}

	public function get_students_by_class_id($classID) {
		$students = array();
		$conn = $this->connectMySQL();
		$stmt = $conn->prepare("SELECT ID, Name, Surname
	                                  FROM Student
	                                  WHERE SpecificClassID = ?
	                                  ORDER BY Surname,Name,ID;");
		if (!$stmt)
			return $students;
		$stmt->bind_param('i', $classID);
		$stmt->execute();
		$res = $stmt->get_result();
		if ($res > 0) {
			while ($row = $res->fetch_assoc()) {
				array_push($students, $row);
			}
		}
		return $students;
	}

	public function get_lecture_by_id($lectureID) {
		$conn = $this->connectMySQL();
		$stmt = $conn->prepare("SELECT TopicRecord.Timestamp as TimeStamp, 
									  TopicRecord.Description as TopicDescription,
									  TopicRecord.ID as TopicRecordID, 
									  Topic.Name as TopicName
									  FROM TopicRecord , Topic
									  WHERE TopicRecord.TopicID=Topic.ID and TopicRecord.ID=?");

		$stmt->bind_param('i', $lectureID);
		$stmt->execute();
		$res = $stmt->get_result();
		if ($res->num_rows <= 0) {
			return false;
		} else {
			$lecture_info = array();
			$lecture_info = $res->fetch_assoc();
			return $lecture_info;
		}
	}

	/**
	 * @param int $studentID
	 * @param int $classID
	 * @param int $subjectID
	 * @param int $mark <=10 >0
	 * @param bool $laude
	 * @param string $timestamp (Y-m-d H:i:s)
	 * @return true on success or false on failure
	 */
	public function insert_grade($studentID, $subjectID, $mark, $laude, $timestamp) {
		if ($mark < 1 or $mark > 10) return false;
		if ($laude != 0 and $laude != 1) return false;

		if ($mark != 10 and $laude == true) return false;
		if (calendar::validate_date($timestamp) == false) return false;

		$teacherID = $_SESSION['teacherID'];

		$conn = $this->connectMySQL();

		if ($this->is_teacher_of_the_student($studentID)) {
			$sql = $conn->prepare("INSERT INTO MarksRecord (StudentID, Mark, TeacherID, TopicID, Timestamp, Laude) VALUES (?,?,?,?,?,?);");
			$sql->bind_param('iiiisi', $studentID, $mark, $_SESSION['teacherID'], $subjectID, $timestamp, $laude);
			return $sql->execute();
		} else {
			return false;
		}
	}

	/**
	 * this function register a student with given studentID as absent on date timestamp.
	 *
	 * @param $studentID
	 * @param $timestamp in the format "Y-m-d H:i:s"
	 *
	 * @return true|false on success or not
	 */
	public function register_absence($studentID, $timestamp) {
		$teacherID = $_SESSION['teacherID'];
		if (!calendar::validate_date($timestamp))
			return false;
		if (!calendar::by_the_end_of_the_week(strtotime(date("Y-m-d H:i:s")), strtotime($timestamp))) return false;
		$y_m_d = date("Y-m-d", strtotime($timestamp));
		$classID = $this->is_teacher_of_the_student($studentID);
		//$ret = "studentID : ".$studentID." - classID : ".$classID . " - date : ".$y_m_d." - student was absent: ".$this->student_was_absent($y_m_d,$studentID);
		if ($classID != false and $classID > 0 and $this->student_was_absent($y_m_d, $studentID) == false) {
			$conn = $this->connectMySQL();
			$sql = "INSERT INTO NotPresentRecord (ID,StudentID,SpecificClassID,Date,Late,ExitHour) VALUES (NULL,?,?,?,0,0);";
			$stmt = $conn->prepare($sql);
			//$ret.=" - stmt : ".$stmt->sqlstate;
			if ($stmt) {
				$stmt->bind_param('iis', $studentID, $classID, $y_m_d);
				return $stmt->execute();
			}
		}
		return false;
	}

	/**
	 * this function is used to check whether this teacher is actually a teacher of the student passed as parameter.
	 *
	 * @param $studentID --> id of the student
	 *
	 * @return bool|string --> ID of the class of the student if all operation are successful
	 *                     --> false if there are no classes in common between student and teacher
	 *                     --> 'err' if there was an error in the query
	 */
	public function is_teacher_of_the_student($studentID) {
		$teacherID = $_SESSION['teacherID'];
		$conn = $this->connectMySQL();
		$sql1 = "   SELECT TopicTeacherClass.SpecificClassID
                    FROM TopicTeacherClass, Student
                    WHERE TopicTeacherClass.TeacherID = ? 
                    AND Student.ID = ?
                    AND TopicTeacherClass.SpecificClassID = Student.SpecificClassID";
		$stmt = $conn->prepare($sql1);

		if ($stmt) {
			$stmt->bind_param('ii', $teacherID, $studentID);
			$stmt->execute();
			$res = $stmt->get_result();
			if ($res->num_rows > 0) {
				$row = $res->fetch_row();
				return $row[0];
			} else {
				return false;
			}
		}
		return 'err';
	}

	/**
	 * This function is used to check whether the student with given studentID was absent or not on a given date
	 *
	 *
	 * @param $Y_m_d --> date to check
	 * @param $studentID --> student to check
	 *
	 *
	 * @return bool|string              --> true if student was absent in that date
	 *                                  --> false if he was present or late
	 *                                  --> 'err' if an error in the query occurred
	 */
	public function student_was_absent($Y_m_d, $studentID) {
		$conn = $this->connectMySQL();
		$sql2 = "SELECT *
                 FROM NotPresentRecord
                 WHERE NotPresentRecord.Date = ?
                 AND NotPresentRecord.StudentID = ?
                 AND ExitHour = 0;";
		$stmt = $conn->prepare($sql2);
		if ($stmt) {
			$stmt->bind_param('si', $Y_m_d, $studentID);
			$stmt->execute();
			$res = $stmt->get_result();
			if ($res->num_rows > 0) {
				return true;
			} else {
				return false;
			}
		}
		return 'err';

	}

	/**
	 * the function both works if previously absent or not
	 * @param $studentID
	 * @param $timestamp in the format "Y-m-d H:i:s"
	 * @return true|false on success or not
	 */
	public function register_late_arrival($studentID, $timestamp) {
		$teacherID = $_SESSION['teacherID'];

		if (!calendar::validate_date($timestamp)) return false;

		if (!calendar::by_the_end_of_the_week(strtotime(date("Y-m-d H:i:s")), strtotime($timestamp))) return false;

		$y_m_d_timestamp = date("Y-m-d", strtotime($timestamp));
		$hours_per_school_day = calendar::get_hours_per_school_day();

		$conn = $this->connectMySQL();

		$sql = "   SELECT COUNT(*)
                    FROM NotPresentRecord
                    WHERE NotPresentRecord.Date = '$y_m_d_timestamp'
                    AND NotPresentRecord.StudentID = '$studentID'
                    AND ExitHour = 0";

		if (($classID = $this->is_teacher_of_the_student($studentID)) and $result = $conn->query($sql)) {

			$row = $result->fetch_array();
			$absent = $row[0]; //it means multiple possibilities: late arrival, absent, early exit...
			$result->close();

			if ($absent) {
				$sql = $conn->prepare("UPDATE NotPresentRecord SET Late = 1, ExitHour = $hours_per_school_day WHERE StudentID = ? AND Date = ?");
				$sql->bind_param('is', $studentID, $y_m_d_timestamp);
				return $sql->execute();
			} else {
				$sql = $conn->prepare("INSERT INTO NotPresentRecord(StudentID,SpecificClassID,Date,Late,ExitHour) VALUES (?,?,'$y_m_d_timestamp',1,$hours_per_school_day)");
				$sql->bind_param('ii', $studentID, $classID);
				return $sql->execute();
			}
		} else {
			printf("Error message: %s\n", $conn->error);
			return false;
		}
	}

	/**
	 * the function both works if previously absent or not
	 * @param $studentID
	 * @param $timestamp in the format "Y-m-d H:i:s"
	 * @param $newExitHour in the range 0-6
	 * @return true|false on success or not
	 */
	public function register_early_exit($studentID, $timestamp, $newExitHour) {
		//todo check if the format of newExitHour need to be changed
		$teacherID = $_SESSION['teacherID'];
        if(!isset($studentID) || !isset($timestamp) || !isset($newExitHour)) return false;

        if($newExitHour > calendar::get_hours_per_school_day()) return false;

		if (!calendar::validate_date($timestamp)) return false;

		if (!calendar::by_the_end_of_the_week(strtotime(date("Y-m-d H:i:s")), strtotime($timestamp))) return false;

		$y_m_d_timestamp = date("Y-m-d", strtotime($timestamp));
		$hours_per_school_day = calendar::get_hours_per_school_day();

		$conn = $this->connectMySQL();

		$sql = "   SELECT COUNT(*)
                    FROM NotPresentRecord
                    WHERE NotPresentRecord.Date = '$y_m_d_timestamp'
                    AND NotPresentRecord.StudentID = '$studentID'
                    AND ExitHour = 0";

		if (($classID = $this->is_teacher_of_the_student($studentID)) and $result = $conn->query($sql)) {

			$row = $result->fetch_array();
			$absent = $row[0]; //it means multiple possibilities: late arrival, absent, early exit...
			$result->close();

			if ($absent) {
				$sql = $conn->prepare("UPDATE NotPresentRecord SET ExitHour = ? WHERE StudentID = ? AND Date = ?;");
				$sql->bind_param('is', $studentID, $y_m_d_timestamp);
				return $sql->execute();
			} else {
				$sql = $conn->prepare("INSERT INTO NotPresentRecord(StudentID,SpecificClassID,Date,Late,ExitHour) VALUES (?,?,'$y_m_d_timestamp',0,?);");
				$sql->bind_param('iii', $studentID, $classID, $newExitHour);
				return $sql->execute();
			}
		} else {
			printf("Error message: %s\n", $conn->error);
			return false;
		}
	}

	/**
	 * @param $assignmentDescription
	 * @param $topicID
	 * @param $timestamp
	 * @param $classID
	 * @return bool
	 * Function that given in input parameters, registers the assignment to the DB
	 */
	public function insert_new_assignment($assignmentDescription, $topicID, $timestamp, $classID) {
		$conn = $this->connectMySQL();
		//$stmt = $conn->prepare("INSERT INTO Homework (TeacherID, Deadline, Description, TopicID, SpecificClassID) VALUES (?,?,?,?,?);");

		$stmt = $conn->prepare("INSERT INTO Homework (Description, SpecificClassID, TeacherID, Deadline, TopicID) VALUES (?,?,?,?,?);");
		if ($stmt == false)
			return false;

		$actual_date = strtotime(date("Y-m-d H:i:s"));
		// given unix timestamp
		$assignment_date = strtotime($timestamp);
		// secondi in una settimana
		//if ($assignment_date>$actual_date)
		//  return false;
        $teacherID = $this->get_teacher_ID();
		//$stmt->bind_param('issii', $_SESSION['teacherID'], $timestamp, $assignmentDescription, $topicID, $classID);
		$stmt->bind_param('siisi', $assignmentDescription, $classID, $teacherID, $timestamp, $topicID);
		return $stmt->execute();//True || False
	}

	// Override of parent method, also check if the id was sent correctly
	public function is_logged() {
		$cond = parent::is_logged() && $this->get_teacher_ID() != -1;
		return $cond;
	}


	public function get_uploaded_material() {

		$teacherID = $_SESSION['teacherID'];
		$uploaded = array();
		$conn = $this->connectMySQL();

		$query = "SELECT DISTINCT ucd.ID, FileName, ucd.Description,YearClassID, Section, Topic.Name, Date
			FROM UploadedClassDocuments ucd, TopicTeacherClass ttc, Topic, SpecificClass sc
			WHERE ucd.SpecificClassID=ttc.SpecificClassID 
      AND ucd.SubjectID=Topic.ID
      AND ucd.SpecificClassID=sc.ID
			AND TeacherID=?;";

		$sql = $conn->prepare($query);
		if (!$sql) {
			return false;
		}
		$sql->bind_param('i', $teacherID);
		$sql->execute();
		$res = $sql->get_result();
		if ($res > 0) {
			while ($row = $res->fetch_assoc()) {
				array_push($uploaded, $row);
			}
		}
		return $uploaded;


//		$uploaded['Filename'] = "File1";
//		$uploaded['Class'] = "1A";
//		$uploaded['Subject'] = "Science";
//		$uploaded['Timestamp'] = date("Y-m-d H:i:s");

//		return array($uploaded);
	}

	/**
	 * @param int $subjectID
	 * @param int $classID
	 * @param string $fname
	 * @param string $servername : the name of file as it is on the server in upload folder
	 * @param string $description
	 * @return true on success or false on failure
	 */
	public function insert_material($fname, $servername, $specificClassID, $description, $subjectID) {
		# TODO:
		# Missing: checks on (class, subject) (is learned by this teacher?)
		# Should the teacher be warned if a file was already uploaded with this name ?
		$teacherID = $_SESSION['teacherID'];

		$conn = $this->connectMySQL();

		#INSERT INTO `UploadedClassDocuments` (`ID`, `FileName`, `DiskFileName`, `SpecificClassID`, `Description`, `Date`, `SubjectID`) VALUES (NULL, 'aaa', 'aa', '1', 'aaaa', CURRENT_TIMESTAMP, '3')
		#if ($this->is_teacher_of_the_class($studentID)) {
		$sql = $conn->prepare("INSERT INTO UploadedClassDocuments(FileName, DiskFileName, SpecificClassID, Description, SubjectID) VALUES (?,?,?,?,?);");
		if (!$sql) {
			return false;
		}
		$sql->bind_param('ssisi', $fname, $servername, $specificClassID, $description, $subjectID);
		return $sql->execute();
		#} else {
		#    return false;
		#}
	}

	public function register_note_record($studentID, $noteID) {
	    if(!isset($studentID) || !isset($noteID))
	        return false;
		$conn = $this->connectMySQL();
		$stmt = $conn->prepare("INSERT INTO NoteRecord (ID,StudentID,NoteID) VALUES (NULL,?,?);");
		if (!$stmt)
			return false;
		$stmt->bind_param("ii", $studentID, $noteID);
		return $stmt->execute();
	}

	public function register_new_note($date, $classID, $note) {
	    if (!isset($date)||!isset($classID)||!isset($note)) return false;
		if (!calendar::validate_date($date)) return false;

		if (!calendar::by_the_end_of_the_week(strtotime(date("Y-m-d H:i:s")), strtotime($date))) return false;

		$conn = $this->connectMySQL();
        $teacherID = $this->get_teacher_ID();
		$stmt = $conn->prepare("INSERT INTO Note (ID,TeacherID,SpecificClassID,Date,Description) VALUES (NULL,?,?,?,?);");
		if (!$stmt)
			return -1;
		$stmt->bind_param("iiss", $this->get_teacher_ID(), $classID, $date, $note);

		if (!$stmt->execute())
			return -2;
		$stmt = $conn->prepare("SELECT Max(ID) 
                                      FROM Note
                                      WHERE TeacherID = ?
                                        AND SpecificClassID = ?
                                        AND Date = ?
                                        AND Description = ?;");
		if (!$stmt)
			return -3;
		$stmt->bind_param("iiss", $this->get_teacher_ID(), $classID, $date, $note);
		if (!$stmt->execute())
			return -4;
		$res = $stmt->get_result();

		return $res->fetch_row()[0];
	}
}