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

	public function testGetClassIDFromYearSection() {
		$officer = new officer();
		$this->assertEquals(-1, $officer->get_classID_from_yearSection(null, 'A'));
		$this->assertEquals(-1, $officer->get_classID_from_yearSection(1, null));
		$this->assertEquals(1, $officer->get_classID_from_yearSection(1, 'A'));
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
		$usr_id = 44;
		$child_info = array();
		$child_info['cf_0'] = 'RRQDWW41C60G670Z';

		$res = $officer->add_new_parent($usr_id, $child_info);
		$this->assertTrue($res, $this->printErrorMessage('testAddNewParent', 'returned value should be true'));

		$child_info['cf_1'] = 'ZGSQPD62P61F443K';

		$res = $officer->add_new_parent($usr_id, $child_info);
		$this->assertTrue($res, $this->printErrorMessage('testAddNewParent', 'returned value should be true'));

		$res = $officer->add_new_parent(null, $child_info);
		$this->assertFalse($res, $this->printErrorMessage('testAddNewParent', 'returned value should be false'));

		$child_info['cf_0'] = null;
		$child_info['cf_1'] = null;
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

	public function testGetParentsWithoutAccessCredentials() {
		$off = new officer();

		$res = $off->get_parents_without_access_credentials();
		$this->assertEquals(0, sizeof($res));

		$off->add_new_user("Jon", "Snow", "kingIn@the.north");
		$res = $off->get_parents_without_access_credentials();
		$this->assertEquals(1, sizeof($res));
	}

	public function testGenerateAndRegisterPassword() {
		$off = new officer();

		$res = $off->generate_and_register_password(null);
		$this->assertEquals("", $res);

		$off->add_new_user("Jon", "Snow", "kingIn@the.north");
		$par = $off->get_parents_without_access_credentials();

		$res = $off->generate_and_register_password($par[0]['ID']);
		$this->assertNotEquals("", $res);
	}

	public function testGetClassList() {
		$off = new officer();

		$classes = $off->get_class_list();
		$this->assertNotEquals(0, sizeof($classes));
	}

	public function testGetStudentsByClassID() {
		$off = new officer();

		// Invalid class
		$res = $off->get_students_by_class_ID(-3);
		$this->assertEquals(0, sizeof($res));

		$res = $off->get_students_by_class_ID(1);
		$this->assertNotEquals(0, sizeof($res));
	}

	public function testRemoveStudentFromClass() {
		$off = new officer();

		$classless = $off->retrieve_classless_students();

		$class1 = $off->get_students_by_class_ID(1);

		if (sizeof($class1) != 0) {
			foreach ($class1 as $student) {
				$off->remove_student_from_class($student['ID']);
			}
		}
		$newClassless = $off->retrieve_classless_students();
		$this->assertEquals(sizeof($newClassless), sizeof($classless) + sizeof($class1));
	}

	public function testAddStudentToCLass() {
		$off = new officer();
		$classless = $off->retrieve_classless_students();
		$class1 = $off->get_students_by_class_ID(1);

		if (sizeof($class1) != 0) {
			foreach ($class1 as $student) {
				$off->remove_student_from_class($student['ID']);
			}
		}

		$newClassless = $off->retrieve_classless_students();
		foreach ($newClassless as $student) {
			$off->add_student_to_class($student['ID'],1);
		}

		$newClass1 = $off->get_students_by_class_ID(1);

		$this->assertEquals(sizeof($newClass1), sizeof($class1) + sizeof($classless));
	}

	public function testGetTeacherTopic(){
		$off = new officer();

		$res = $off->get_teacher_topic(-1);
		$this->assertEquals(0,sizeof($res));

		$res = $off->get_teacher_topic(1);
		$this->assertNotEquals(0,sizeof($res));
	}

	public function testDeleteTimetable(){
		$off = new officer();

		if($off->exists_timetable(2)){
			$off->delete_timetable(2);
		}

		$this->assertFalse($off->exists_timetable(2));

	}


	public function testSetTimetableClass() {
		$_SESSION['officerID'] = 1;
		$off1 = new officer();

		// Simulating POST data ====> topicID|teacherID|{insert,update}
		$fakePostData = "null|null|null";
		$classID = 1;

		// Delete existing timetable
		$off1->delete_timetable($classID);

		// All null values
		$timetable = $this->generateAndFillTimetableMatrix($fakePostData);
		$this->assertFalse($off1->set_timetable_class($timetable, null));

		// Valid class ID null timetable
		$this->assertFalse($off1->set_timetable_class($timetable, $classID));

		// Null value on insert/update
		$fakePostData = "1|1|null";
		$timetable = $this->generateAndFillTimetableMatrix($fakePostData);
		$this->assertFalse($off1->set_timetable_class($timetable, $classID));

		// Null value on topicID
		$fakePostData = "null|1|insert";
		$timetable = $this->generateAndFillTimetableMatrix($fakePostData);
		$this->assertFalse($off1->set_timetable_class($timetable, $classID));

		// Null value on teacherID
		$fakePostData = "1|null|insert";
		$timetable = $this->generateAndFillTimetableMatrix($fakePostData);
		$this->assertFalse($off1->set_timetable_class($timetable, $classID));

		// Insert valid timetable
		$timetable = $this->generateTimetableMatrixClass1A();
		$this->assertTrue($off1->set_timetable_class($timetable, $classID));

		// Update all timetable
		$timetable = $this->generateTimetableMatrixClass1AUpdate();
		$this->assertTrue($off1->set_timetable_class($timetable, $classID));
	}

	public function testGetTimetableClass() {
		$_SESSION['officerID'] = 1;
		$off1 = new officer();
		$classID = 1;

		// Wrong class ID
		$this->assertEquals(0, $off1->get_timetable_by_class(-1));

		// Null class ID
		$this->assertEquals(0, $off1->get_timetable_by_class(null));

		// Delete existing timetable
		$off1->delete_timetable($classID);

		// Insert valid timetable
		$timetable = $this->generateTimetableMatrixClass1A();
		$this->assertTrue($off1->set_timetable_class($timetable, $classID));

		// Get all the timetable
		$this->assertEquals(25, sizeof($off1->get_timetable_by_class($classID)));
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

	/**
	 * utility function for generating a matrix containing fake post data for testing set timetable
	 * @param $postData
	 * @return mixed
	 */
	private function generateAndFillTimetableMatrix($postData) {
		for ($day = 0; $day < 5; $day++) {
			$teacher_hour_day[$day] = array();
			for ($hour = 0; $hour < 6; $hour++) {
				$teacher_hour_day[$day][$hour] = $postData;
			}
		}
		return $teacher_hour_day;
	}

	private function generateTimetableMatrixClass1A(){
		$timetable[0][0] = "1|1|insert";
		$timetable[0][1] = "1|1|insert";
		$timetable[0][2] = "2|1|insert";
		$timetable[0][3] = "2|1|insert";
		$timetable[0][4] = "4|4|insert";
		$timetable[0][5] = ".|.|nothing";
		$timetable[1][0] = "3|3|insert";
		$timetable[1][1] = "3|3|insert";
		$timetable[1][2] = "4|4|insert";
		$timetable[1][3] = "3|3|insert";
		$timetable[1][4] = "5|5|insert";
		$timetable[1][5] = ".|.|nothing";
		$timetable[2][0] = "3|3|insert";
		$timetable[2][1] = "3|3|insert";
		$timetable[2][2] = "5|5|insert";
		$timetable[2][3] = "6|6|insert";
		$timetable[2][4] = "6|6|insert";
		$timetable[2][5] = "8|8|insert";
		$timetable[3][0] = "3|3|insert";
		$timetable[3][1] = "7|7|insert";
		$timetable[3][2] = "8|8|insert";
		$timetable[3][3] = "8|8|insert";
		$timetable[3][4] = "8|8|insert";
		$timetable[3][5] = ".|.|nothing";
		$timetable[4][0] = "8|8|insert";
		$timetable[4][1] = "8|8|insert";
		$timetable[4][2] = "7|7|insert";
		$timetable[4][3] = "3|3|insert";
		$timetable[4][4] = ".|.|nothing";
		$timetable[4][5] = ".|.|nothing";

		return $timetable;
	}

	private function generateTimetableMatrixClass1AUpdate(){

		$timetable[0][0] = "7|7|update";
		$timetable[0][1] = "1|1|insert";
		$timetable[0][2] = "2|1|insert";
		$timetable[0][3] = "2|1|insert";
		$timetable[0][4] = "4|4|insert";
		$timetable[0][5] = ".|.|nothing";
		$timetable[1][0] = "3|3|insert";
		$timetable[1][1] = "3|3|insert";
		$timetable[1][2] = "4|4|insert";
		$timetable[1][3] = "3|3|insert";
		$timetable[1][4] = "5|5|insert";
		$timetable[1][5] = ".|.|nothing";
		$timetable[2][0] = "3|3|insert";
		$timetable[2][1] = "3|3|insert";
		$timetable[2][2] = "5|5|insert";
		$timetable[2][3] = "6|6|insert";
		$timetable[2][4] = "6|6|insert";
		$timetable[2][5] = "8|8|insert";
		$timetable[3][0] = "3|3|insert";
		$timetable[3][1] = "7|7|insert";
		$timetable[3][2] = "8|8|insert";
		$timetable[3][3] = "8|8|insert";
		$timetable[3][4] = "8|8|insert";
		$timetable[3][5] = ".|.|nothing";
		$timetable[4][0] = "8|8|insert";
		$timetable[4][1] = "8|8|insert";
		$timetable[4][2] = "1|1|update";
		$timetable[4][3] = "3|3|insert";
		$timetable[4][4] = ".|.|nothing";
		$timetable[4][5] = ".|.|nothing";

		return $timetable;
	}
}
