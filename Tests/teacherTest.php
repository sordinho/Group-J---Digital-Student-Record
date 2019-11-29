<?php


use PHPUnit\Framework\TestCase;

require_once 'testUtility.php';
require_once '../classes/user.class.php';
require_once '../classes/teacher.class.php';
require_once '../classes/calendar.class.php';

class teacherTest extends TestCase {

	public static function setUpBeforeClass(): void {
		createTestDatabase();
	}

	public static function tearDownAfterClass(): void {
		dropTestDatabase();
	}

	public function testModify_lecture_topic() {
		$_SESSION["teacherID"] = 1;
		$topicID = 1;
		$description = "Test topic description";
		$modifiedDescription = "MODIFIED Test topic description";
		$teacherObject = new Teacher();
		//$teacherID = $teacherObject->get_teacher_ID();
		$timestamp = date("Y-m-d H:i:s");
		$classID = 1;
		// Insert a topic record to modify
		$this->assertNotNull($teacherObject->insert_new_lecture_topic($description, $topicID, $timestamp, $classID));

		$topicRecordID = perform_SELECT_return_single_value("SELECT ID FROM TopicRecord WHERE Timestamp ='$timestamp'");
		//printf("topicRecordID: %d\n",$topicRecordID);

		// Try to modify the topic record
		$teacherObject->modify_lecture_topic($modifiedDescription, $topicRecordID);

		$this->assertEquals($modifiedDescription, perform_SELECT_return_single_value("SELECT Description FROM TopicRecord WHERE ID=$topicRecordID"), "Test non superato");
		//printf("%s",$modifiedDescription);

	}

	public function testInsert_new_lecture_topic() {
		//TODO
		// variables
		$_SESSION["teacherID"] = 1;
		$teacherObject = new Teacher();
		$topicID = 1;
		$description = "Lecture 1 topic 1";
		$dateActualDate = date("Y-m-d H:i:s");
		$classID = 1;
		//printf("DbName: %s",DBName);

		//perform insertion in the DB
		$this->assertNotNull($teacherObject->insert_new_lecture_topic($description, $topicID, $dateActualDate, $classID));

		$topicRecordID = perform_SELECT_return_single_value("SELECT ID FROM TopicRecord WHERE Timestamp ='$dateActualDate'");
		//printf("topicRecordID: %d\n",$topicRecordID);

		$count = perform_SELECT_return_single_value("SELECT COUNT(*) FROM TopicRecord WHERE ID =$topicRecordID");
		//printf("Count: %d\n",$count);

		$this->assertEquals($count, 1, "Test non superato!");

		$this->assertTrue(perform_INSERT_or_DELETE("DELETE FROM TopicRecord WHERE ID=$topicRecordID"));

		//Prints for debug
		//printf("TeacherID: %d\nDateActual: %s\nDescription: %s\nTopicID: %d\nSpecifiClassID: %d",$teacherID,$dateActualDate,$description,$topicID,$specificClassID);

	}

	public function testGet_topics() {
		$this->assertTrue(true);
	}

	public function testGet_students_by_class_id() {

	}

	public function testGet_assigned_classes(){

	}

	public function testInsert_grade() {
		$_SESSION['teacherID'] = 1;
		$teacherObject = new Teacher();
		$studentID = 1;
		$classID = 1;
		$subjectID = 1;
		$mark = -5;
		$laude = false;
		$timestamp = date("Y-m-d H:i:s");
//		$timestamp =time();

		// wrong value for mark
		$this->assertFalse($teacherObject->insert_grade($studentID, $classID, $subjectID, $mark, $laude, $timestamp));

		$mark = 10;
		$laude = 15;
		//wrong value for laude
		$this->assertFalse($teacherObject->insert_grade($studentID, $classID, $subjectID, $mark, $laude, $timestamp));

		$mark = 4;
		$laude = true;
		// wrong combination of mark laude
		$this->assertFalse($teacherObject->insert_grade($studentID, $classID, $subjectID, $mark, $laude, $timestamp));

		$classID = -1;
		// wrong classID
		$this->assertFalse($teacherObject->insert_grade($studentID, $classID, $subjectID, $mark, $laude, $timestamp));

		$timestamp = date('Y-m-d H:i:s',strtotime("2011-01-07"));
		$mark = 9;
		$laude = false;
		$this->assertTrue($teacherObject->insert_grade($studentID, $classID, $subjectID, $mark, $laude, $timestamp));
		$count = perform_SELECT_return_single_value("SELECT COUNT(*) FROM MarksRecord WHERE StudentID =$studentID AND Timestamp='$timestamp'");
		$this->assertEquals(1, $count, "Test non superato!");

        $timestamp = date('Y-m-d H:i:s',strtotime("2011-01-10"));
		$mark = 10;
		$laude = true;
		$this->assertNotNull($teacherObject->insert_grade($studentID, $classID, $subjectID, $mark, $laude, $timestamp));
		$count = perform_SELECT_return_single_value("SELECT COUNT(*) FROM MarksRecord WHERE StudentID =$studentID AND Timestamp='$timestamp'");
		$this->assertEquals(1, $count, "Test non superato!");
	}

    public function testInsert_new_assignment() {
        //TODO
        // variables
        $_SESSION["teacherID"] = 1;
        $teacherObject = new Teacher();
        $topicID = 1;
        $description = "Assignment 1 topic 1";
        $dateActualDate = date("Y-m-d H:i:s");
        $classID = 1;
        //printf("DbName: %s",DBName);

        //perform insertion in the DB
        //$assignmentDescription, $topicID, $timestamp, $classID
        $this->assertNotNull($teacherObject->insert_new_assignment($description, $topicID, $dateActualDate, $classID));

        $AssignmentID = perform_SELECT_return_single_value("SELECT ID FROM Assignment WHERE Timestamp ='$dateActualDate'");
        //printf("AssignmentID: %d\n",$AssignmentID);

        $count = perform_SELECT_return_single_value("SELECT COUNT(*) FROM Assignment WHERE ID =$AssignmentID");
        //printf("Count: %d\n",$count);

        $this->assertEquals($count, 1, "Test non superato!");

        $this->assertTrue(perform_INSERT_or_DELETE("DELETE FROM TopicRecord WHERE ID=$AssignmentID"));

        //Prints for debug
        //printf("TeacherID: %d\nDateActual: %s\nDescription: %s\nTopicID: %d\nSpecifiClassID: %d",$teacherID,$dateActualDate,$description,$topicID,$specificClassID);

    }

}
