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
	
	/**
	 * Generate a random string, using a cryptographically secure 
	 * pseudorandom number generator (random_int)
	 * 
	 * For PHP 7, random_int is a PHP core function
	 * 
	 * @param int $length      How many characters do we want?
	 * @param string $keyspace A string of all possible characters
	 *                         to select from
	 * @return string
	 */
	private function random_str(
		$length,
		$keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
	) {
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

	private function generate_and_register_password(){
		$rand_pass = $this->random_str(10);
		$options = [
			//'salt' => custom_function_for_salt(), //eventually define a function to generate a  salt
			'cost' => 12 // default is 10, better have a little more security
		];
		$hashed_password = password_hash($rand_pass, PASSWORD_DEFAULT, $options);
		// TODO: Perform insert query

		return $rand_pass;  // will be used by the caller to send email
	}
}