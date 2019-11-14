<?php

class officer extends user {

	private $officer_id = null;

	public function __construct($data = array()) {
		parent::__construct($data);
		$this->officer_id = $_SESSION['officerID'];
	}

	// Return the parent ID from parent table
	public function get_officer_ID() {
		return isset($_SESSION['officerID']) ? $_SESSION['officerID'] : -1;
	}

	// Enroll a new student (anagraphic datas that should be saved into db)
	// keys of student_info are: name, surname, avgLastSchool, CF
	public function enroll_student($student_info) {
		$si = $student_info; 
		if(isset($si["name"]) && isset($si["suname"]) && isset($si["avgLastSchool"]) && isset($si["CF"]) ){
			return false;
		}
		$classID = -1;
		// immetricolation info inserted also now? 
		$actual_year= strtotime(date("Y"));
		$conn = $this->connectMySQL();
		//(`ID`, `Name`, `Surname`, `AverageLastSchool`, `CF`, `SpecificClassID`) 
		$stmt = $conn->prepare("INSERT INTO Student(Name, Surname, AverageLastSchool, CF, SpecificClassID) VALUES (?,?,?,?,?)");
		$stmt->bind_param('ssfsi', $student_info["name"], $student_info["surname"], $student_info["avgLastSchool"], $student_info["CF"], $classID);
		$stmt->execute();
		return $stmt->get_result();//True || False
	}	

/*
 * questa funzione riceve nome, cognome, email, informazioni sui figli e numero di figli
 * e provvede a inserire un nuovo utente nella tabella user e un nuovo parent nella tabella parent (una entry per ogni
 * figlio)
 * */
	public function register_new_parent($name,$surname,$email,$child_info,$child_N){
        //todo check correctness
	    if(!isset($name)||!isset($surname)||!isset($email)||!isset($child_info)||empty($child_info))
	        return false;
	    $conn = $this->connectMySQL();
        $stmt = $conn->prepare("INSERT INTO User (Name, Surname, Email, Password, UserGroup) VALUES (?,?,?,?);");
        $stmt->bind_param('sssss', $name, $surname, $email, '', 'parent');
        $stmt->execute();
        if(!$stmt->get_result())
            return false;
        $stmt = $conn->prepare("SELECT ID
                                      FROM User
                                      WHERE Name = ?
                                        AND Surname= ? 
                                        AND Email = ?
                                        AND UserGroup = 'parent';");
        $stmt->bind_param('sss', $name, $surname, $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res->num_rows<=0)
            return false;
        $parent_id = $res->fetch_row()[0];
        for($i = 0; $i < $child_N;$i++){
            $stmt2 =$conn->prepare("SELECT ID FROM Student WHERE CF = ?;");
            $stmt2->bind_param('s',$child_info['cf_'.$i]);
            $stmt2->execute();
            $res = $stmt2->get_result();
            if($res->num_rows <=0)
                return false;
            $student_id = $res->fetch_row()[0];
            $stmt2 = $conn->prepare("INSERT INTO Parent(StudentID, UserID) VALUES(?,?);");
            $stmt2->bind_param('ii',$student_id,$parent_id);
            $stmt2->execute();
            if(!$stmt2->get_result())
                return false;
        }
        return true;//True || False
    }
}