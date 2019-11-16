<?php

require_once 'user.class.php';
class teacher extends user
{
    protected $teacherID = null;
    protected $name = null;
    protected $surname = null;
    protected $email = null;

	public function __construct($data = array()) {
		parent::__construct($data);
		$this->teacherID = $data['teacherID'];
	}

	private function by_the_end_of_the_week($actual_date, $lecture_date) {
		// secondi in una settimana
		$week = 60 * 60 * 24 * 7;
		$max_limit = $lecture_date + $week;
		//cond 1 : non possiamo registrare in anticipo
		if ($actual_date < $lecture_date)
			return false;
		//cond 2 : non posso registrare un topic dopo la fine della settimana (dopo la domenica)
		//cond 2.1 : actual_date < lecture_date+ 60*60*24*7
		if ($actual_date >= $max_limit)
			return false;
		//cond 2.2 : giorno della settimana di lecture precedente alla domenica della settimana stessa
		//todo : check correctness
		$lecture_day_of_the_week_n = date('N', $lecture_date);
		$actual_day_of_the_week_n = date('N', $actual_date);
		if ($actual_day_of_the_week_n < $lecture_day_of_the_week_n)
			return false;
		return true;
	}

	/*
	 * @lectureDescritpion --> string with the description of a single lecture
	 * @topicID            --> Id of the subject
	 * @timestamp          --> date of the lecture "yyyy-mm-dd hh:mm:ss"
	 *
	 * return               true            if successful
	 *                      false           otherwise
	 * */
	public function insert_new_lecture_topic($lectureDescription, $topicID, $timestamp) {
		//todo : come arriva la data dell'inserzione? UNIX timestamp o già formattata?
		//       come salviamo nel db il timestamp? Al momento sto ipotizzando arrivino
		//       nello stesso formato di actual_date
		$classID = -1;
		// actual unix timestamp
		$actual_date = strtotime(date("Y-m-d H:i:s"));
		// given unix timestamp
		$lecture_date = strtotime($timestamp);
		// secondi in una settimana
		if (!$this->by_the_end_of_the_week($actual_date, $lecture_date))
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
		if($stmt == false)
		    return false;
		$stmt->bind_param('issii', $this->teacherID, $timestamp, $lectureDescription, $topicID, $classID);
		return $stmt->execute();//True || False
	}

	/*
	 * @newDescription     --> string with the new description of a single lecture (already registeret)
	 * @topicID            --> Id of the subject
	 *
	 * return               true            if successful
	 *                      false           otherwise
	 * */

	public function modify_lecture_topic($newDescription,$topicRecordID) {
		if (!isset($topicRecordID) || !isset($newDescription)) {
			return false;
		}

		$conn = $this->connectMySQL();
		$stmt = $conn->prepare("SELECT Timestamp,TeacherID FROM TopicRecord WHERE ID = ?;");
		if(!$stmt)
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
			if (!$this->by_the_end_of_the_week($actual_date, $lecture_date))
				return false;
            if($row[1] != $this->teacherID)
                return false;
			$res->close();
			$stmt = $conn->prepare("UPDATE TopicRecord SET Description=? WHERE ID=$topicRecordID");
			if(!$stmt)
			    return false;
			$stmt->bind_param("s", $newDescription);
			return $stmt->execute();
		}
	}

    /*
     * Get the topics information for which the teacher is current in charge of
     *
     * return               empty            if successful
     *                      array of array   otherwise
     * */ 
    public function get_topics(){
        $topics = array();
        // TODO create TopicTeacherClass table logic scheme TopicTeacherClass(TopicID, TeacherID, SpecificClassID)
        // Write correct query, use AS to define alias with following names (TopicID, TopicName, TopicDescription)
        $conn = $this->connectMySQL();
        $stmt = $conn->prepare("SELECT t.ID as TopicID, tt.TeacherID as TeacherID, u.Name as TeacherName, u.Surname as TeacherSurname, t.Name as TopicName, t.Description as TopicDescription From Topic t, TeacherTopic tt, User u, Teacher tc where tt.TopicID=t.ID and u.ID=tc.UserID and tt.TeacherID=tc.ID and tc.ID=?");
        $stmt->bind_param('ii',$topicID);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res->num_rows<=0){
            return false;
        }else{
            $row = $res->fetch_assoc();
            array_push($topics, $row);
        }
        return $topics;
    }

	// Return the teacher ID from teacher table
	public function get_teacher_ID() {
		return isset($_SESSION['teacherID']) ? $_SESSION['teacherID'] : -1;
	}
}