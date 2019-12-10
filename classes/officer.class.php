<?php
require_once "user.class.php";

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
        if(!$this->check_fiscal_code($si['CF']))
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
    public function remove_Student_From_Class($studentID)
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
            $stamp=$row["YearClassID"]."°".$row["Section"];
            array_push($IDs, $row);
        }
        $res->close();
        return $stamp;
    }

}