<?php

use PHPUnit\Framework\TestCase;

require_once 'testUtility.php';
require_once "../classes/officer.class.php";
require_once "../classes/user.class.php";


class officerTest extends TestCase {
	private function printErrorMessage($testName, $optionalMsg) {
		$toReturn = "officerTest: error in " . $testName;
		if (isset($optionalMsg) && !empty($optionalMsg))
			$toReturn .= " --> " . $optionalMsg . ".";
		return $toReturn;
	}

	public static function setUpBeforeClass(): void {
		createTestDatabase();
	}

	public static function tearDownAfterClass(): void {
		dropTestDatabase();
	}

	public function testGetOfficerID() {
		$_SESSION['officerID'] = 1;
		$officer = new officer();
		$this->assertEquals(1, $officer->get_officer_ID(), $this->printErrorMessage("testGetOfficerID", "wrong returned value"));
		unset($_SESSION['officerID']);
		$this->assertEquals(-1, $officer->get_officer_ID(), $this->printErrorMessage("testGetOfficerID", "returned value should be -1"));
	}

	public function testEnrollStudent() {
		$student_info = array();
		$student_info['name'] = 'Jon';
		$student_info['surname'] = 'Snow';
		$student_info['avgLastSchool'] = 7.55;
		$student_info['CF'] = 'VSHXMG88R28D865H';
		$officer = new officer();
		$res = $officer->enroll_student($student_info);

		$this->assertTrue($res, $this->printErrorMessage('testEnrollStudent', 'returned value should be true'));

		unset($student_info['name']);
		$res = $officer->enroll_student($student_info);
		$this->assertFalse($res, $this->printErrorMessage('testEnrollStudent', 'returned value should be false'));

		$student_info['name'] = 'Jon';
		unset($student_info['surname']);
		$res = $officer->enroll_student($student_info);
		$this->assertFalse($res, $this->printErrorMessage('testEnrollStudent', 'returned value should be false'));

		$student_info['surname'] = 'Snow';
		unset($student_info['avgLastSchool']);
		$res = $officer->enroll_student($student_info);
		$this->assertFalse($res, $this->printErrorMessage('testEnrollStudent', 'returned value should be false'));

		$student_info['avgLastSchool'] = 7.55;
		unset($student_info['CF']);
		$res = $officer->enroll_student($student_info);
		$this->assertFalse($res, $this->printErrorMessage('testEnrollStudent', 'returned value should be false'));
	}

	public function testAddNewUser() {
		$officer = new officer();
		$res = $officer->add_new_user(null, null, null);
		$this->assertEquals(-1, $res, $this->printErrorMessage("testAddNewUser", "returned value should be -1"));
		$res = $officer->add_new_user(null, "SNOW", null);
		$this->assertEquals(-1, $res, $this->printErrorMessage("testAddNewUser", "returned value should be -1"));
		$res = $officer->add_new_user("Jon", null, null);
		$this->assertEquals(-1, $res, $this->printErrorMessage("testAddNewUser", "returned value should be -1"));
		$res = $officer->add_new_user(null, null, "kingIn@the.north");
		$this->assertEquals(-1, $res, $this->printErrorMessage("testAddNewUser", "returned value should be -1"));
		$res = $officer->add_new_user("Jon", "Snow", null);
		$this->assertEquals(-1, $res, $this->printErrorMessage("testAddNewUser", "returned value should be -1"));
		$res = $officer->add_new_user(null, "Snow", "kingIn@the.north");
		$this->assertEquals(-1, $res, $this->printErrorMessage("testAddNewUser", "returned value should be -1"));
		$res = $officer->add_new_user("Jon", null, "kingIn@the.north");
		$this->assertEquals(-1, $res, $this->printErrorMessage("testAddNewUser", "returned value should be -1"));

		$res = $officer->add_new_user("Jon", "Snow", "kingIn@the.north");
		$this->assertTrue($res > 0, $this->printErrorMessage("testAddNewUser", "returned value should not be -1"));
	}

	public function testAddNewParent() {
		$officer = new officer();
		$usr_id = 5;
		$child_info = array();
		$child_info['cf_0'] = 'YPBDDN86C62G482B';
		$childN = 1;
		$res = $officer->add_new_parent($usr_id, $child_info);
		$this->assertTrue($res, $this->printErrorMessage('testAddNewParent', 'returned value should be true'));
		$child_info['cf_1'] = 'YPBDDN86C62G482B';
		$childN++;
		$res = $officer->add_new_parent($usr_id, $child_info);
		$this->assertTrue($res, $this->printErrorMessage('testAddNewParent', 'returned value should be true'));
		$childN++;
		$res = $officer->add_new_parent($usr_id, $child_info);
		$this->assertFalse($res, $this->printErrorMessage('testAddNewParent', 'returned value should be false'));
		unset($child_info);
		$child_info = array();
		$res = $officer->add_new_parent($usr_id, $child_info);
		$this->assertFalse($res, $this->printErrorMessage('testAddNewParent', 'returned value should be false'));
	}

	public function testRemoveUser() {
		$validID = 2;
		$invalidID = -500;
		$officer = new officer();
		$res = $officer->remove_user($invalidID);
		$this->assertFalse($res, $this->printErrorMessage('testRemoveUser', 'returned value should be false'));
		$res = $officer->remove_user($validID);
		$this->assertTrue($res, $this->printErrorMessage('testRemoveUser', 'returned value should be true'));
	}

	public function testPublishCommunication() {
		// Test with not logged officer
		$off1 = new officer();
		$this->assertEquals(-1, $off1->publish_communication("testTitle", "Test Description"));

		// Logged in
		$_SESSION['officerID'] = 1;


		// Empty communication
		$this->assertEquals(0, $off1->publish_communication("", ""));

		// Empty title
		$this->assertEquals(-3, $off1->publish_communication("", "no title description"));

		// Empty description
		$this->assertEquals(-4, $off1->publish_communication("no description title", ""));

		// True values
		$this->assertEquals(1, $off1->publish_communication("testTitle", "Test Description"));
	}

	public function testSetTimetableClass() {
		$_SESSION['officerID'] = 1;
		$off1 = new officer();

		// Simulating POST data ====> topicID|teacherID|{insert,update}
		$fakePostData = "null|null|null";
		$classID = 1;

		// All null values
		$timetable = $this->generateAndFillTimetableMatrix($fakePostData);
		$this->assertFalse($off1->set_timetable_class($timetable,null));

		// Valid class ID null timetable
		$this->assertFalse($off1->set_timetable_class($timetable,$classID));

		// Null value on insert/update
		$fakePostData = "1|1|null";
		$timetable = $this->generateAndFillTimetableMatrix($fakePostData);
		$this->assertFalse($off1->set_timetable_class($timetable,$classID));

		// Null value on topicID
		$fakePostData = "null|1|insert";
		$timetable = $this->generateAndFillTimetableMatrix($fakePostData);
		$this->assertFalse($off1->set_timetable_class($timetable,$classID));

		// Null value on teacherID
		$fakePostData = "1|null|insert";
		$timetable = $this->generateAndFillTimetableMatrix($fakePostData);
		$this->assertFalse($off1->set_timetable_class($timetable,$classID));

		// Insert valid timetable
		$fakePostData = "1|1|insert";
		$timetable = $this->generateAndFillTimetableMatrix($fakePostData);
		$this->assertTrue($off1->set_timetable_class($timetable,$classID));

		// Update all timetable
		$fakePostData = "8|6|update";
		$timetable = $this->generateAndFillTimetableMatrix($fakePostData);
		$this->assertTrue($off1->set_timetable_class($timetable,$classID));
	}

	public function testGetTimetableClass(){
		$_SESSION['officerID'] = 1;
		$off1 = new officer();
		$classID = 1;

		// Wrong class ID
		$this->assertEquals(0, $off1->get_timetable_by_class(-1));

		// Null class ID
		$this->assertEquals(0, $off1->get_timetable_by_class(null));

		// Empty timetable
		$this->assertEquals(0, sizeof($off1->get_timetable_by_class($classID)));

		// Insert valid timetable
		$fakePostData = "1|1|insert";
		$timetable = $this->generateAndFillTimetableMatrix($fakePostData);
		$this->assertTrue($off1->set_timetable_class($timetable,$classID));

		// Get all the timetable
		$this->assertEquals(30, sizeof($off1->get_timetable_by_class($classID)));
	}

	/**
	 * utility function for generating a matric containing fake post data for testing set timetable
	 * @param $postData
	 */
	private function generateAndFillTimetableMatrix($postData){
		for ($day = 0; $day < 5; $day++) {
			$teacher_hour_day[$day] = array();
			for ($hour= 0; $hour<6; $hour++){
				$teacher_hour_day[$day][$hour] = $postData;
			}
		}
	}
}
