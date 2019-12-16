<?php
require_once "user.class.php";

define('DAYS', 5);
define('HOUR_SLOTS', 6);

class officer extends user
{

    public function __construct()
    {
        parent::__construct();
    }

    // Return the parent ID from parent table
    public function get_officer_ID()
    {
        return isset($_SESSION['officerID']) ? $_SESSION['officerID'] : -1;
    }

    // Enroll a new student (anagraphic datas that should be saved into db)
    // keys of student_info are: name, surname, avgLastSchool, CF
    public function enroll_student($student_info)
    {
        $si = $student_info;
        if (!(isset($si["name"]) && isset($si["surname"]) && isset($si["avgLastSchool"]) && isset($si["CF"]))) {
            return false;
        }

        //check fiscal code
        if (!$this->check_fiscal_code($si['CF']))
            return false;

        $classID = -1;
        // immetricolation info inserted also now?
        $actual_year = strtotime(date("Y"));
        $conn = $this->connectMySQL();
        //(`ID`, `Name`, `Surname`, `AverageLastSchool`, `CF`, `SpecificClassID`)
        //INSERT INTO `Student` (`ID`, `Name`, `Surname`, `AverageLastSchool`, `CF`, `SpecificClassID`) VALUES
        $stmt = $conn->prepare("INSERT INTO Student (ID, Name, Surname, AverageLastSchool, CF, SpecificClassID) VALUES (NULL,?,?,?,?,?);");
        $stmt->bind_param('ssdsi', $student_info["name"], $student_info["surname"], $student_info['avgLastSchool'], $student_info["CF"], $classID);
        return $stmt->execute();
    }

    /*
     * Given a year and a section returns the classID
     * @param year int
     *        select string
     * @return -1 --> class does not exists
     *          id--> classID
     * */
    public function get_classID_from_yearSection($year, $section)
    {
        if (!isset($year) || !isset($section)) {
            return -1;
        }
        $conn = $this->connectMySQL();
        $stmt = $conn->prepare("SELECT ID FROM SpecificClass WHERE YearClassID = ? AND Section = ?;");
        if (!$stmt) return -1;
        $stmt->bind_param("is", $year, $section);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows <= 0) return -1;
        return $res->fetch_row()[0];
    }

    /*
     * register a new parent in the user table.
     *
     * @return          -1 if the operation is not successful
     *                  parent's user_id if the operation is successful
     * */
    public function add_new_user($name, $surname, $email)
    {
        if (!isset($name) || !isset($surname) || !isset($email))
            return -1;
        $conn = $this->connectMySQL();
        $stmt = $conn->prepare("INSERT INTO User (ID, Name, Surname, Email, Password, UserGroup) VALUES (NULL ,?,?,?,'','parent');");
        if (!$stmt)
            return -2;

        $stmt->bind_param('sss', $name, $surname, $email);
        if (!$stmt->execute())
            return -3;
        $stmt = $conn->prepare("SELECT ID
                                      FROM User
                                      WHERE Name = ?
                                        AND Surname= ? 
                                        AND Email = ?
                                        AND UserGroup = 'parent';");
        if (!$stmt)
            return -4;
        $stmt->bind_param('sss', $name, $surname, $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows <= 0)
            return -5;
        return $res->fetch_row()[0];
    }

    /*
     * given a user_id inserts $child_N rows in Parent table.
     *
     * @return          false if the operation was not successful
     *                  true if the operation was successful
     * */
    public function add_new_parent($user_id, $child_info)
    {
        $conn = $this->connectMySQL();
        for ($i = 0; $i < sizeof($child_info); $i++) {
            $stmt2 = $conn->prepare("SELECT ID FROM Student WHERE CF = ?;");
            if (!$stmt2)
                return false;
            $stmt2->bind_param('s', $child_info['cf_' . $i]);
            $stmt2->execute();
            $res = $stmt2->get_result();
            if ($res->num_rows <= 0)
                return false;
            $student_id = $res->fetch_row()[0];
            $stmt2 = $conn->prepare("INSERT INTO Parent(StudentID, UserID) VALUES(?,?);");
            if (!$stmt2)
                return false;
            $stmt2->bind_param('ii', $student_id, $user_id);
            if (!$stmt2->execute())
                return false;
        }
        return true;//True || False
    }

    /*
     * removes a user from the USER table and all his entries from Parent table
     *
     * @param userID ---> the id of the user to be removed
     *
     * @return true ---> operation successful
     *         false --> operation unsuccessful
     * */
    public function remove_user($userID)
    {
        if (!isset($userID))
            return false;
        $conn = $this->connectMySQL();
        $stmt = $conn->prepare("SELECT * FROM User WHERE ID = ?;");
        if (!$stmt)
            return false;
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows != 1)
            return false;
        $stmt = $conn->prepare("DELETE FROM User WHERE ID = ?;");
        if (!$stmt)
            return false;
        $stmt->bind_param("i", $userID);
        if (!$stmt->execute())
            return false;
        $stmt = $conn->prepare("DELETE FROM Parent WHERE UserID = ?;");
        IF (!$stmt)
            return false;
        $stmt->bind_param("i", $userID);
        return $stmt->execute();
    }

    public function get_parents_without_access_credentials()
    {
        $conn = $this->connectMySQL();
        //todo : to be edited
        // Se un parent non ha password cosa c'è in quel campo della tabella User? Stringa vuota o altro?
        $res = $conn->query("SELECT ID,Email FROM User WHERE UserGroup = 'parent' AND Password = ''");
        if ($res->num_rows <= 0)
            return array();
        $IDs = array();
        for ($i = 0; $i < $res->num_rows; $i++) {
            $row = $res->fetch_assoc();
            array_push($IDs, $row);
        }
        $res->close();
        return $IDs;
    }

    public function generate_and_register_password($userID)
    {
        $rand_pass = $this->random_str(10);
        $options = [
            //'salt' => custom_function_for_salt(), //eventually define a function to generate a  salt
            'cost' => 12 // default is 10, better have a little more security
        ];
        $hashed_password = password_hash($rand_pass, PASSWORD_DEFAULT, $options);
        //todo check correctness
        $conn = $this->connectMySQL();
        $stmt = $conn->prepare("UPDATE User SET Password = ? WHERE ID = ?");
        if (!$stmt)
            return "";
        $stmt->bind_param("si", $hashed_password, $userID);
        if (!$stmt->execute())
            return "";
        return $rand_pass;  // will be used by the caller to send email
    }

    /**
     * @return array
     * Class that returns the array of all entries in the SpecificClass table in the DB
     */
    public function get_class_list()
    {
        $conn = $this->connectMySQL();

        $res = $conn->query("SELECT ID, YearClassID, Section FROM SpecificClass");
        if ($res->num_rows <= 0)
            return array();
        $IDs = array();
        for ($i = 0; $i < $res->num_rows; $i++) {
            $row = $res->fetch_assoc();
            array_push($IDs, $row);
        }
        $res->close();
        return $IDs;
    }

    /**
     * @param $classID
     * @return array
     * Function that given the classID, returns the array with ID, Name, Surname of every student of the requested class
     */
    public function get_students_by_class_ID($classID)
    {
        $conn = $this->connectMySQL();

        $res = $conn->query("SELECT ID, Name, Surname FROM Student WHERE SpecificClassID=$classID ORDER BY Surname,Name");
        if ($res->num_rows <= 0)
            return array();
        $IDs = array();
        for ($i = 0; $i < $res->num_rows; $i++) {
            $row = $res->fetch_assoc();
            array_push($IDs, $row);
        }
        $res->close();
        return $IDs;
    }

    public function retrieve_classless_students()
    {
        /*$conn = $this->connectMySQL();

        $res= $conn->query("SELECT ID,Name,Surname
                                  FROM Student
                                  WHERE SpecificClassID = -1
                                  ORDER BY Surname,Name;");
        if($res->num_rows<=0)
            return array();
        $students = array();
        for($i = 0; $i < $res->num_rows;$i++){
            $row = $res->fetch_assoc();
            array_push($students,$row);
        }
        $res->close();
        return $students;*/
        return $this->get_students_by_class_ID(-1);
    }

    /**
     * @param $studentID
     * Function that given the studentID removes it from the class it is actually assighed to (sets specificClassID=-1)
     * returns the id of the class the student was in, to be able to redirect to that class composition modification
     */
    public function remove_student_from_class($studentID)
    {
        $conn = $this->connectMySQL();


        $res = $conn->query("SELECT SpecificClassID FROM Student WHERE ID=$studentID");
        $row = $res->fetch_assoc();
        $classID = $row['SpecificClassID'];

        $res->close();

        if ($conn->query("UPDATE Student SET SpecificClassID=-1 WHERE ID=$studentID") === TRUE) {

        } else {
            echo "Error updating record: " . $conn->error;
            return -1;
        }
        $conn->close();
        return $classID;
    }

    /**
     * @param $studentID
     * @param $classID
     * @return bool
     */
    public function add_student_to_class($studentID, $classID)
    {
        $conn = $this->connectMySQL();
        $returnState = false;
        if ($conn->query("UPDATE Student SET SpecificClassID=$classID WHERE ID=$studentID") === TRUE) {
            $returnState = true;
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $conn->close();
        return $returnState;
    }

    // Override of parent method, also check if the id was sent correctly
    public function is_logged()
    {
        $cond = parent::is_logged() && $this->get_officer_ID() != -1;
        return $cond;
    }

    public function get_class_stamp_by_id($classID)
    {
        $conn = $this->connectMySQL();

        $res = $conn->query("SELECT YearClassID, Section FROM SpecificClass WHERE ID=$classID");
        if ($res->num_rows <= 0)
            return array();
        $IDs = array();
        for ($i = 0; $i < $res->num_rows; $i++) {
            $row = $res->fetch_assoc();
            $stamp = $row["YearClassID"] . "°" . $row["Section"];
            array_push($IDs, $row);
        }
        $res->close();
        return $stamp;
    }

    public function get_teacher_topic($classID)
    {

        $conn = $this->connectMySQL();

        $res = $conn->query("SELECT User.Name as TeacherName, User.Surname as TeacherSurname, Topic.Name as TopicName, Topic.ID as TopicID, Teacher.ID as TeacherID
                                    FROM TopicTeacherClass, Topic, Teacher, User
                                    WHERE TopicTeacherClass.TeacherID=Teacher.ID AND TopicTeacherClass.TopicID=Topic.ID AND Teacher.UserID=user.ID AND TopicTeacherClass.SpecificClassID=$classID
                                    GROUP BY User.Name, User.Surname, Topic.Name, Topic.ID, Teacher.ID");

        if ($res->num_rows <= 0)
            return array();
        $IDs = array();
        for ($i = 0; $i < $res->num_rows; $i++) {
            $row = $res->fetch_assoc();
            array_push($IDs, $row);
        }
        $res->close();
        return $IDs;
    }

    /**
     * @param $classID
     * @param $timetable [$giorno][$hour]
     * @return bool
     */
    public function upload_timetable_by_csv($classID, $timetable)
    {
        // 5 righe (5 giorni ) x 6 ore
        // in ogni casella teacherID_topicID
        return true;
    }

    /**
     * topicID|teacherID|insert
     * @param $data
     * @return bool
     */
    public function set_timetable_class($data)
    {
        if (!(isset($data["hours"]) && isset($data["classID"]))) {
            return false;
        }

        $conn = $this->connectMySQL();
        //user.Name, user.Surname, topic.Name, topic.ID, teacher.ID
        for ($i = 0; $i < calendar::get_day_per_school_week(); $i++) {
            for ($j = 0; $j < calendar::get_hour_per_school_day(); $j++) {
                $pieces = explode("_", $data["hours"][$i][$j]);
                $stmt = $conn->prepare("INSERT INTO Timetables (TeacherID, TopicID, SpecificClassID,HourSlot,DayOfWeek) VALUES (?,?,?,?,?);");
                $stmt->bind_param('iiiii', intval($pieces[1]), intval($pieces[0]), $data["classID"], $j, $i);
                if (!$stmt->execute()) {
                    return false;
                }
            }
            $j = 0;
        }
        return true;
    }

    public function get_timetable_by_class($classID)
    {
        $officerID = $this->get_officer_ID();
        $timetable = array();
        if (!isset($classID) || $classID == -1) {
            return 0; // no class
        } elseif ($officerID != -1) {
            $conn = $this->connectMySql();
            /*
             * take the full timetable for a given class
             */
            $stmt = $conn->prepare("SELECT *
                                            FROM Timetables
                                            WHERE SpecificClassID=?");
            $stmt->bind_param('i', $classID);
            if (!$stmt->execute()) {
                return -1; // failed
            }

            $res = $stmt->get_result();

            /*
             * there are already some occupied hour slots for the given class
             */
            if ($res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    /*
                     * append to each hour slot teacher surname and topic name
                     */
                    $stmt1 = $conn->prepare("SELECT u.Name as TeacherName, u.Surname as TeacherSurname, tc.Name as TopicName
                                                    FROM User u, Teacher t, Topic tc, SpecificClass sc
                                                    WHERE t.UserID=u.ID
                                                    AND sc.ID=?
                                                    AND t.ID=?
                                                    AND tc.ID=?
                                                    ");
                    $stmt1->bind_param('iii', $classID, $row['TeacherID'], $row['TopicID']);

                    /*
                     * get the remaining topics not present in that hour slot
                     */
                    $stmt2 = $conn->prepare("SELECT u.Name as TeacherName, u.Surname as TeacherSurname, tc.Name as TopicName, tc.ID as TopicID, t.ID as TeacherID
                                                    FROM User u, Teacher t, TopicTeacherClass ttc, Topic tc
                                                    WHERE u.ID=t.UserID
                                                    AND t.ID=ttc.TeacherID
                                                    AND tc.ID=ttc.TopicID
                                                    AND ttc.SpecificClassID = ?
                                                    AND tc.ID NOT IN (
                                                        SELECT TopicID
                                                        FROM Timetables
                                                        WHERE SpecificClassID = ?
                                                        AND TopicID = ?
                                                        AND HourSlot = ?
                                                        AND DayOfWeek = ?
                                                    )");
                    $stmt2->bind_param("iiiii", $classID, $classID, $row['TopicID'], $row['HourSlot'], $row['DayOfWeek']);
                    if (!$stmt1->execute()) {
                        return -1; // failed
                    }
                    $res1 = $stmt1->get_result();
                    if (!$stmt2->execute()) {
                        return -1; // failed
                    }
                    $res2 = $stmt2->get_result();
                    /*
                     * teacher and topic information must be stored in a single row
                     * every class must have more than one topic
                     */
                    if ($res1->num_rows == 1 && $res2->num_rows > 0) {
                        $info = $res1->fetch_assoc();
                        $row['TeacherName'] = $info['TeacherName'];
                        $row['TeacherSurname'] = $info['TeacherSurname'];
                        $row['TopicName'] = $info['TopicName'];
                        $row['action'] = 'update';
                        /*
                         *  on top of each hour slot must be the already inserted topic
                         */
                        $timetable[$row['HourSlot']][$row['DayOfWeek']][] = $row;
                        while ($row2 = $res2->fetch_assoc()) {
                            $row2['action'] = 'insert';
                            $timetable[$row['HourSlot']][$row['DayOfWeek']][] = $row2;
                        }
                    } else {
                        return -3; // queries fail
                    }
                }
            }

            return $timetable;
        }

        return -2; // not logged in
    }

    /**
     * @param $title : title of the communication
     * @param $description : body of the communication
     * @return int :    -4 -> empty description
     *                    -3 -> empty title
     *                    -2 -> error in query
     *                    -1 -> not logged in
     *                    0  -> empty communication
     *                    1  -> success
     */
    public function publish_communication($title, $description)
    {
        $officerID = $this->get_officer_ID();
        if ($title === "" && $description === "") {
            return 0; // empty communication
        } elseif ($title === "") {
            return -3; // empty title
        } elseif ($description === "") {
            return -4; // empty description
        } elseif ($officerID != -1) {
            $conn = $this->connectMySql();
            $stmt = $conn->prepare("INSERT INTO Communication (Title, Description, OfficerID) VALUES (?, ?, ?)");
            $stmt->bind_param('ssi', $title, $description, $officerID);
            if (!$stmt->execute()) {
                return -2; // failed insertion
            }
            return 1; // successfully insertion
        }

        return -1; // not logged in
    }


}