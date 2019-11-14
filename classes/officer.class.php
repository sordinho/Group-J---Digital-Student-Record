<?php

class Officer extends user {

	private $officer_id = null;

	public function __construct($data = array()) {
		parent::__construct($data);
		$this->officer_id = $_SESSION['officerID'];
	}

	// Return the parent ID from parent table
	public function get_officer_ID() {
		return isset($_SESSION['officerID']) ? $_SESSION['officerID'] : -1;
	}
}