<?php

require_once 'user.class.php';

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
			$stmt->bind_param("si", $newDescription,$topicRecordID);
			return $stmt->execute();
		}
	}

	/*
	 * Get the topics information for which the teacher is current in charge of
	 *
	 * return               empty            if successful
	 *                      array of array   otherwise
	 * */
	public function get_topics($selectedClass=0) {
		$topics = array();
		// TODO create TopicTeacherClass table logic scheme TopicTeacherClass(TopicID, TeacherID, SpecificClassID)
		// Write correct query, use AS to define alias with following names (TopicID, TopicName, TopicDescription)
		$conn = $this->connectMySQL();
		$teacherID = $this->get_teacher_ID();

		// todo manage class selection
		if($selectedClass){
			$stmt = $conn->prepare("SELECT ttc.SpecificClassID as ClassID, tc.ID as TopicID, tc.Name as TopicName, 
                                                tc.Description as TopicDescription 
                                            FROM TopicTeacherClass as ttc, Topic as tc, Teacher as t 
                                            WHERE ttc.TeacherID=t.ID and tc.ID=ttc.TopicID and t.ID=? and ttc.SpecificClassID=?");
			$stmt->bind_param('ii', $teacherID, $selectedClass);

		} else{
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
			$row = $res->fetch_assoc();
			array_push($topics, $row);
		}
		return $topics;
	}

	// Return the teacher ID from teacher table
	public function get_teacher_ID() {
		return isset($_SESSION['teacherID']) ? $_SESSION['teacherID'] : -1;
	}

	/*
	 * get ClassID, TopicName, TopicDescription for a given teacherID
	 */
	public function get_assigned_classes() {
		$classes = array();
		$conn = $this->connectMySQL();
		$stmt = $conn->prepare("SELECT ttc.ID as ClassID, t.Name as TopicName, t.Description as TopicDescription, sc.YearClassID as YearClass, sc.Section as Section 
                                      from TopicTeacherClass ttc, Topic as t, SpecificClass as sc 
                                      WHERE ttc.TopicID=t.ID and sc.ID = ttc.SpecificClassID and TeacherID=?");
		$teacherID = $this->get_teacher_ID();
		$stmt->bind_param('i', $teacherID);
		$stmt->execute();
		$res = $stmt->get_result();
		if ($res->num_rows <= 0) {
			return false;
		} else {
			$row = $res->fetch_assoc();
			array_push($classes, $row);
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

	public function get_students_by_class_id($classID){
	    $students = array();
	    $conn = $this->connectMySQL();
	    $stmt = $conn->prepare("SELECT ID, Name, Surname
	                                  FROM Student
	                                  WHERE SpecificClassID = ?
	                                  ORDER BY Surname,Name,ID;");
	    if(!$stmt)
	        return $students;
	    $stmt->bind_param('i',$classID);
	    $stmt->execute();
	    $res = $stmt->get_result();
	    if($res>0){
	        while($row = $res->fetch_assoc()){
	            array_push($students,$row);
            }
        }
	    return $students;
    }

	public function get_lecture_by_id($lectureID){
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
            $lecture_info=$res->fetch_assoc();
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
    public function insert_grade($studentID, $classID, $subjectID, $mark, $laude, $timestamp) {
	    if ($mark < 1 or $mark > 10) return false;
	    if ($laude != 0 and $laude != 1) return false;

        $found = false;
        foreach ($this->get_assigned_classes() as $classes_info)
            if (in_array($classID, $classes_info)) $found = true;
        if (!$found) return false;

        if ($mark != 10 and $laude == true) return false;
        if (calendar::validate_date($timestamp) == false) return false;

        $teacherID = $_SESSION['teacherID'];

        $conn = $this->connectMySQL();
        $sql = "    SELECT COUNT(*) 
                    FROM TopicTeacherClass, Student
                    WHERE TopicTeacherClass.TeacherID = $teacherID
                    AND TopicTeacherClass.TopicID = $subjectID
                    AND Student.ID = $studentID
                    AND TopicTeacherClass.SpecificClassID = Student.SpecificClassID";

        if ($result = $conn->query($sql)) {
            $row = $result->fetch_array();
            $teachInThatClass = $row[0];

            $result->close();

            if ($teachInThatClass == 1) {
                $sql = $conn->prepare("INSERT INTO MarksRecord (StudentID, Mark, TeacherID, TopicID, Timestamp, Laude) VALUES (?,?,?,?,?,?);");
                $sql->bind_param('iiiisi', $studentID, $mark, $_SESSION['teacherID'], $subjectID, $timestamp, $laude);
                return $sql->execute();
            } else {
                return false;
            }
        } else {
            printf("Error message: %s\n", $conn->error);
            return false;
        }
	}

	public function register_late_arrival($studentID) {
        /*the function is thought to be used only on previous-registered absences*/

        $teacherID = $_SESSION['teacherID'];

        $conn = $this->connectMySQL();
        $sql = "    SELECT COUNT(*) 
                    FROM TopicTeacherClass, Student
                    WHERE TopicTeacherClass.TeacherID = $teacherID
                    AND TopicTeacherClass.TopicID = $subjectID
                    AND Student.ID = $studentID
                    AND TopicTeacherClass.SpecificClassID = Student.SpecificClassID";
    }
}