<?php

class sparent extends user {
	private $parent_id = null;

	//private $childs = array();

	public function __construct($data = array()) {
		parent::__construct($data);
		$this->parent_id = $_SESSION['parentID'];
	}

	//returns the result of the query that selects all the grades of @childID
	public function get_grades($childID) {

		if (!isset($childID)) {
			return array();
		}
		$conn = $this->connectMySql();
		$stmt = $conn->prepare("SELECT t.Name, Mark, Timestamp, u.Surname 
                FROM  Topic t, MarksRecord M, Teacher Te,User u
                WHERE M.TeacherID = Te.ID AND Te.UserID=u.ID AND t.ID=M.TopicID
                    AND M.StudentID = ?");
		$stmt->bind_param('s', $childID);
		$stmt->execute();
		return $stmt->get_result();
	}

	// Register the childs in a session
	public function retrieve_and_register_childs() {

		$childs = array();
		$conn = $this->connectMySql();
		$stmt = $conn->prepare("SELECT StudentID
              FROM Parent
              WHERE ID = ?;");
		$stmt->bind_param('d', $this->parent_id);
		$stmt->execute();
		$res = $stmt->get_result();
		while ($row = $res->fetch_row()) {
			array_push($childs, $row[0]);
		}
		$_SESSION['childsID'] = $childs;
		//$_SESSION['childNames'] = $child_names;
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

}