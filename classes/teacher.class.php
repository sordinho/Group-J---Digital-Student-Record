<?php

require_once 'user.class.php';

class teacher extends user
{

    public function __construct()
    {
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
    public function insert_new_lecture_topic($lectureDescription, $topicID, $timestamp, $classID)
    {
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
    public function modify_lecture_topic($newDescription, $topicRecordID)
    {
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
     * Get the topics information for which the teacher is current in charge of
     *
     * return               empty            if successful
     *                      array of array   otherwise
     * */
    public function get_topics($selectedClass = 0)
    {
        $topics = array();
        // TODO create TopicTeacherClass table logic scheme TopicTeacherClass(TopicID, TeacherID, SpecificClassID)
        // Write correct query, use AS to define alias with following names (TopicID, TopicName, TopicDescription)
        $conn = $this->connectMySQL();
        $teacherID = $this->get_teacher_ID();

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
            $row = $res->fetch_assoc();
            array_push($topics, $row);
        }
        return $topics;
    }

    // Return the teacher ID from teacher table
    public function get_teacher_ID()
    {
        return isset($_SESSION['teacherID']) ? $_SESSION['teacherID'] : -1;
    }

    /*
     * get ClassID, TopicName, TopicDescription for a given teacherID
     */
    public function get_assigned_classes()
    {
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
    public function get_topics_record()
    {
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

    public function get_students_by_class_id($classID)
    {
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

    public function get_lecture_by_id($lectureID)
    {
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
    public function insert_grade($studentID, $classID, $subjectID, $mark, $laude, $timestamp)
    {
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
    /**
     * this function register a student with given studentID as absent on date timestamp.
     *
     * @param $studentID
     * @param $timestamp in the format "Y-m-d H:i:s"
     *
     * @return true|false on succes or not
     */
    public function register_absence($studentID,$timestamp){
        $teacherID = $_SESSION['teacherID'];
        if(!calendar::validate_date($timestamp))
            return false;
        $y_m_d = date("Y-m-d",strtotime($timestamp));
        $classID = $this->is_teacher_of_the_student($studentID);
        if($classID != false && $classID > 0 && $this->student_was_absent($y_m_d,$studentID) == false){
            $conn = $this->connectMySQL();
            $sql ="INSERT INTO notpresentrecord (ID,StudentID,SpecificClassID,Date,Late,ExitHour) VALUES (null,?,?,?,0,0);";
            $stmt = $conn->prepare($sql);
            if($stmt){
                $stmt->bind_param('iis',$studentID,$classID,$y_m_d);
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
    public function is_teacher_of_the_student($studentID){
        $teacherID = $_SESSION['teacherID'];
        $conn = $this->connectMySQL();
        $sql1 = "   SELECT COUNT(*),TopicTeacherClass.SpecificClassID
                    FROM TopicTeacherClass, Student
                    WHERE TopicTeacherClass.TeacherID = $teacherID
                    AND Student.ID = ?
                    AND TopicTeacherClass.SpecificClassID = Student.SpecificClassID";
        $stmt = $conn->prepare($sql1);
        if($stmt){
            $stmt->bind_param('i',$studentID);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows>0){
                $row = $res->fetch_row();
                if($row[0]>0){
                    return $row[1]; //return the specific class ID, needed for registering a new absence
                }
            }else{
                return false;
            }
        }
        return 'err';
    }

    /**
     * This function is used to check whether the student with given studentID was absent or not on a given date
     *
     *
     * @param $Y_m_d                    --> date to check
     * @param $studentID                --> student to check
     *
     *
     * @return bool|string              --> true if student was absent in that date
     *                                  --> false if he was present or late
     *                                  --> 'err' if an error in the query occurred
     */
    public function student_was_absent($Y_m_d,$studentID){
        $conn = $this->connectMySQL();
        $sql2 = "   SELECT COUNT(*)
                    FROM NotPresentRecord, Student
                    WHERE NotPresentRecord.Date = ?
                    AND NotPresentRecord.StudentID = ?
                    AND Student.SpecificClassID = NotPresentRecord.SpecificClassID
                    AND ExitHour = 0";
        $stmt = $conn->prepare($sql2);
        if($stmt){
            $stmt->bind_param('si',$Y_m_d,$studentID);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows>0) {
                if ($res->fetch_row()[0] > 0) {
                    return true;
                }
            }else{
                return false;
            }
        }
        return 'err';

    }

    /**
     * the function is to be used only on previously-registered absences = [Late = 0, ExitHour = 0]
     * @param $studentID
     * @param $timestamp in the format "Y-m-d H:i:s"
     * @return true|false on succes or not
     */
    public function register_late_arrival($studentID, $timestamp)
    {
        $teacherID = $_SESSION['teacherID'];

        if (!calendar::validate_date($timestamp)) return false;

        $y_m_d_timestamp = date("Y-m-d", strtotime($timestamp));

        $conn = $this->connectMySQL();
        $sql1 = "   SELECT COUNT(*)
                    FROM TopicTeacherClass, Student
                    WHERE TopicTeacherClass.TeacherID = $teacherID
                    AND Student.ID = $studentID
                    AND TopicTeacherClass.SpecificClassID = Student.SpecificClassID";

        $sql2 = "   SELECT COUNT(*)
                    FROM NotPresentRecord, Student
                    WHERE NotPresentRecord.Date = '$y_m_d_timestamp'
                    AND NotPresentRecord.StudentID = '$studentID'
                    AND Student.SpecificClassID = NotPresentRecord.SpecificClassID
                    AND ExitHour = 0";

        if ($result1 = $conn->query($sql1) and $result2 = $conn->query($sql2)) {
            $row = $result1->fetch_array();
            $teach_in_that_class = $row[0];
            $result1->close();

            $row = $result2->fetch_array();
            $student_was_absent = $row[0];
            $result2->close();

            $exit_hour = calendar::get_hours_per_school_day();

            if ($teach_in_that_class and $student_was_absent) {
                $sql = $conn->prepare("UPDATE NotPresentRecord SET Late = 1, ExitHour = $exit_hour WHERE StudentID = ? AND Date = ?");
                $sql->bind_param('is', $studentID, $y_m_d_timestamp);
                return $sql->execute();
            } else {
                return false;
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
        $stmt = $conn->prepare("INSERT INTO Assignment (TeacherID, Timestamp, Description, TopicID, SpecificClassID) VALUES (?,?,?,?,?);");

        if ($stmt == false)
            return false;


        $actual_date = strtotime(date("Y-m-d H:i:s"));
        // given unix timestamp
        $assignment_date = strtotime($timestamp);
        // secondi in una settimana
        if ($assignment_date>$actual_date)
            return false;

        $stmt->bind_param('issii', $_SESSION['teacherID'], $timestamp, $assignmentDescription, $topicID, $classID);
        return $stmt->execute();//True || False
    }

}