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

	// Enroll a new student (anagraphic date saved into db)
	public function enroll_student($student_info) {
		if(!array_key_exists("name", $student_info) ){
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
}