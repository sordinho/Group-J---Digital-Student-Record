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
                    AND M.StudentID = ?");
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
                                          h.TopicID
                                        FROM
                                          Student s,
                                          SpecificClass sc,
                                          Homework h
                                        WHERE
                                          s.SpecificClassID = sc.ID AND h.SpecificClassID = s.SpecificClassID AND s.ID = ?");
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
		$children[0]["childID"] = "-1";
		return isset($_SESSION['childrenInfo']) ? $_SESSION['childrenInfo'] : $children;
	}

	public function getParentId() {
		//return $this->parent_id;
		return $_SESSION['parentID'];
	}


}