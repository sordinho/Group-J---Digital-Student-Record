<?php


use PHPUnit\Framework\TestCase;

require_once 'testUtility.php';
require_once '../classes/user.class.php';
require_once '../classes/teacher.class.php';
require_once '../classes/calendar.class.php';

class teacherTest extends TestCase
{

    private function printErrorMessage($testName, $optionalMsg)
    {
        $toReturn = "teacherTest: error in " . $testName;
        if (isset($optionalMsg) && !empty($optionalMsg))
            $toReturn .= " --> " . $optionalMsg . ".";
        return $toReturn;
    }

    public static function setUpBeforeClass(): void
    {
        createTestDatabase();
        perform_INSERT_or_DELETE("INSERT INTO User (ID, Name, Surname, Email, Password, UserGroup) VALUES (9999, 'TestName', 'TestSurname', 'TestEmail', '$2y$12\$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by', 'teacher')");
        perform_INSERT_or_DELETE("INSERT INTO Teacher (ID, MeetingHourID, UserID, FiscalCode) VALUES (9999, 0, 9999, '')");

        perform_INSERT_or_DELETE("INSERT INTO TopicTeacherClass (ID, TeacherID, TopicID, SpecificClassID) VALUES
                                        (9998, 1, 1, 1),
                                        (9997, 2, 1, 2),
                                        (9996, 3, 1, 3),
                                        (9995, 7, 4, 3),
                                        (9994, 1, 2, 1),
                                        (9993, 7, 3, 2);");

        perform_INSERT_or_DELETE("INSERT INTO SpecificClass (ID, YearClassID, Section, UploadedPath, CoordinatorTeacherID) VALUES (9999, 1, 'A', '', 1)");
        perform_INSERT_or_DELETE("INSERT INTO TopicTeacherClass (ID, TeacherID, TopicID, SpecificClassID) VALUES (9999, 1, 1, 9999)");

        perform_INSERT_or_DELETE("INSERT INTO Topic (ID, Name, Description) VALUES (9999, 'TopicTest', 'DescriptionTest')");
        perform_INSERT_or_DELETE("INSERT INTO TopicRecord (ID, TeacherID, Description, TopicID, SpecificClassID) VALUES (9999, 1, 'DescriptionTest', 9999, 9999)");
        perform_INSERT_or_DELETE("INSERT INTO TopicRecord (ID, TeacherID, Description, TopicID, SpecificClassID) VALUES (9998, 1, 'DescriptionTest', 9999, 9999)");
        perform_INSERT_or_DELETE("INSERT INTO TopicRecord (ID, TeacherID, Description, TopicID, SpecificClassID) VALUES (9997, 1, 'DescriptionTest', 9999, 9999)");
        perform_INSERT_or_DELETE("INSERT INTO TopicRecord (ID, TeacherID, Description, TopicID, SpecificClassID) VALUES (9996, 1, 'DescriptionTest', 9999, 9999)");

        perform_INSERT_or_DELETE("INSERT INTO Timetables (ID, TeacherID, TopicID, SpecificClassID, DayOfWeek, HourSlot) VALUES (9999, 1, 1, 1, 0, 0)");
        perform_INSERT_or_DELETE("INSERT INTO Timetables (ID, TeacherID, TopicID, SpecificClassID, DayOfWeek, HourSlot) VALUES (9998, 1, 1, 1, 0, 2)");

        perform_INSERT_or_DELETE("INSERT INTO TeacherAvailability (ID, TeacherID, DayOfWeek, HourSlot) VALUES (9999, 9999, 2, 1)");
        perform_INSERT_or_DELETE("INSERT INTO MeetingReservation (ID, ParentID, TeacherAvailabilityID, Date, TimeSlot) VALUES (9999, 2, 9999, '2020-01-15', 0)");

        perform_INSERT_or_DELETE("INSERT INTO Student (ID, Name, Surname, AverageLastSchool, CF, SpecificClassID) VALUES (9999, 'TestName', 'TestSurname', 0, '', 9999)");

        perform_INSERT_or_DELETE("INSERT INTO FinalGrades (ID, StudentID, TopicID, Mark, TermID) VALUES (9999, 9999, 8, 0, 1)");
        perform_INSERT_or_DELETE("INSERT INTO FinalGrades (ID, StudentID, TopicID, Mark, TermID) VALUES (9998, 9999, 1, 0, 1)");
        perform_INSERT_or_DELETE("INSERT INTO FinalGrades (ID, StudentID, TopicID, Mark, TermID) VALUES (9997, 9999, 2, 0, 1)");
        perform_INSERT_or_DELETE("INSERT INTO FinalGrades (ID, StudentID, TopicID, Mark, TermID) VALUES (9996, 9999, 3, 0, 1)");
        perform_INSERT_or_DELETE("INSERT INTO FinalGrades (ID, StudentID, TopicID, Mark, TermID) VALUES (9995, 9999, 4, 0, 1)");
        perform_INSERT_or_DELETE("INSERT INTO FinalGrades (ID, StudentID, TopicID, Mark, TermID) VALUES (9994, 9999, 5, 0, 1)");
        perform_INSERT_or_DELETE("INSERT INTO FinalGrades (ID, StudentID, TopicID, Mark, TermID) VALUES (9993, 9999, 6, 0, 1)");
        perform_INSERT_or_DELETE("INSERT INTO FinalGrades (ID, StudentID, TopicID, Mark, TermID) VALUES (9992, 9999, 7, 0, 1)");
    }

    public static function tearDownAfterClass(): void
    {
        dropTestDatabase();
    }

    public function testModify_lecture_topic_BOUNDARY()
    {
        $_SESSION["teacherID"] = 1;
        $topicID = 1;
        $description = "Test 2 topic 1 description 2";
        $modifiedDescription = "MODIFIED Test topic description";
        $teacherObject = new Teacher();
        $timestamp = date("Y-m-d H:i:s");
        $date1 = date("Y-m-d H:i:s", strtotime("-8 days"));
        $classID = 1;

        $this->assertNotNull($teacherObject->insert_new_lecture_topic($description, $topicID, $date1, $classID));
        // perform_INSERT_or_DELETE("INSERT INTO TopicRecord (TeacherID, Timestamp, Description, TopicID, SpecificClassID) VALUES (1,$date1,$description,1,1);");
        $topicRecordID = perform_SELECT_return_single_value("SELECT ID FROM TopicRecord WHERE Timestamp ='$date1'");

        $res = $teacherObject->modify_lecture_topic($modifiedDescription, $topicRecordID);
        $this->assertFalse($res, $this->printErrorMessage("testModify_lecture_topic_BOUNDARY", "Successful modify with invalid date"));
        $res = $teacherObject->modify_lecture_topic(null, $topicRecordID);
        $this->assertFalse($res, $this->printErrorMessage("testModify_lecture_topic_BOUNDARY", "Successful modify with invalid date"));
        $res = $teacherObject->modify_lecture_topic($modifiedDescription, null);
        $this->assertFalse($res, $this->printErrorMessage("testModify_lecture_topic_BOUNDARY", "Successful modify with invalid date"));
        $res = $teacherObject->modify_lecture_topic(null, null);
        $this->assertFalse($res, $this->printErrorMessage("testModify_lecture_topic_BOUNDARY", "Successful modify with invalid date"));
    }

    public function testModify_lecture_topic()
    {
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

    public function testInsert_new_lecture_topic()
    {
        //TODO
        // variables
        $_SESSION["teacherID"] = 1;
        $teacherObject = new Teacher();
        $topicID = 1;
        $description = "Lecture 1 topic 1";
        $dateActualDate = date("Y-m-d H:i:s");
        $classID = 1;

        //perform insertion in the DB
        $this->assertNotNull($teacherObject->insert_new_lecture_topic($description, $topicID, $dateActualDate, $classID));

        $topicRecordID = perform_SELECT_return_single_value("SELECT ID FROM TopicRecord WHERE Timestamp ='$dateActualDate'");
        //printf("topicRecordID: %d\n",$topicRecordID);

        $count = perform_SELECT_return_single_value("SELECT COUNT(*) FROM TopicRecord WHERE ID =$topicRecordID");
        //printf("Count: %d\n",$count);

        $this->assertEquals($count, 1, "Test non superato!");

        $this->assertTrue(perform_INSERT_or_DELETE("DELETE FROM TopicRecord WHERE ID=$topicRecordID"));
    }

    public function testInsert_new_lecture_topic_BOUNDARY()
    {
        $_SESSION["teacherID"] = 1;
        $teacher = new Teacher();
        $topicID = 1;
        $classID = 1;
        $descr = "Lecture 1.1 Topic 1.1";
        $invalidDate = date("Y-m-d H:i:s", strtotime("-8 days"));
        $invalidDate2 = date("Y-m-d H:i:s", strtotime("+1 days"));
        $this->assertFalse($teacher->insert_new_lecture_topic($descr, $topicID, $invalidDate, $classID), $this->printErrorMessage("testInsert_new_lecture_topic", "Successful insertion with invalid date"));

        $this->assertFalse($teacher->insert_new_lecture_topic($descr, $topicID, $invalidDate2, $classID), $this->printErrorMessage("testInsert_new_lecture_topic", "Successful insertion with invalid date"));
    }

    public function testGet_topics()
    {
        /*INSERT INTO `TopicTeacherClass` (`ID`, `TeacherID`, `TopicID`, `SpecificClassID`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 2),
(3, 3, 1, 3),
(4, 7, 4, 3),
(5, 1, 2, 1),
(6, 7, 3, 2);*/
        $_SESSION['teacherID'] = 1;
        $classID = 1;
        $teacher = new teacher();
        $res = $teacher->get_topics($classID);
        $this->assertNotEquals(1, sizeof($res), $this->printErrorMessage("testGet_topics", "wrong size of returned values"));
        $id1 = $res[0]['TopicID'];

        $this->assertNotEquals(0, $id1, $this->printErrorMessage("testGet_topics", "wrong topic id, returned the dummy one"));
    }

    public function testGet_teacher_ID()
    {
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $this->assertEquals(1, $teacher->get_teacher_ID(), $this->printErrorMessage("testGet_teacher_ID", "wrong id returned"));
    }

    public function testGet_teacher_ID_BOUNDARY()
    {
        unset($_SESSION['teacherID']);
        $teacher = new teacher();
        $this->assertEquals(-1, $teacher->get_teacher_ID(), $this->printErrorMessage("testGet_teacher_ID_BOUNDARY", "wrong id returned"));
    }

    public function testGet_assigned_classes_names()
    {
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $res = $teacher->get_assigned_classes_names();

        if ($res == false)
            $this->fail($this->printErrorMessage("testGet_assigned_classes_names", "function returned false instead of an array"));
        $this->assertNotEquals(0, sizeof($res), $this->printErrorMessage("testGet_assigned_classes_names", "wrong size of returned values"));
        $id1 = $res[0]['ClassID'];
        $this->assertNotEquals(0, $id1, $this->printErrorMessage("testGet_assigned_classes_names", "wrong returned class id"));
    }

    public function testGet_students_by_class_id()
    {
        $_SESSION['teacherID'] = 1;
        $classID = 1;
        $teacher = new teacher();
        $res = $teacher->get_students_by_class_id($classID);
        $this->assertNotEquals(0, sizeof($res), $this->printErrorMessage("testGet_students_by_class_id", "wrong size of returned array"));

        $res = $teacher->get_students_by_class_id(-2);
        $this->assertEquals(0, sizeof($res), $this->printErrorMessage("testGet_students_by_class_id", "wrong size of returned array"));

    }

    public function testGet_daily_absences()
    {
        /*INSERT INTO `NotPresentRecord` (`ID`, `StudentID`, `SpecificClassID`, `Date`, `Late`, `ExitHour`) VALUES
(1, 1, 1, '2019-11-28', 1, 4),
(2, 2, 1, '2019-11-28', 0, 0),
(3, 3, 1, '2019-11-28', 0, 4),
(12, 6, 1, '2019-12-02', 0, 0),
(13, 3, 1, '2019-12-02', 1, 6),
(14, 2, 1, '2019-12-02', 1, 6),
(15, 5, 1, '2019-12-02', 0, 0),
(16, 4, 1, '2019-12-02', 0, 0);*/
        $_SESSION['teacherID'] = 1;
        $classID = 1;
        $date = date("Y-m-d", mktime(0, 0, 0, 12, 02, 2019));
        $teacher = new teacher();
        $res = $teacher->get_daily_absences($date, $classID);
        $this->assertNotEquals(0, sizeof($res), $this->printErrorMessage("testGet_daily_absences", "wrong size of returned array"));
        $this->assertEquals(5, sizeof($res), $this->printErrorMessage("testGet_daily_absences", "wrong size of returned array"));
        $date = date("Y-m-d", mktime(0, 0, 0, 12, 01, 2019));
        $res = $teacher->get_daily_absences($date, $classID);
        $this->assertEquals(0, sizeof($res), $this->printErrorMessage("testGet_daily_absences", "wrong size of returned array"));
    }

    public function testGet_assigned_classes()
    {
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $res = $teacher->get_assigned_classes();

        if ($res == false)
            $this->fail($this->printErrorMessage("testGet_assigned_classes", "function returned false instead of an array"));
        $this->assertNotEquals(0, sizeof($res), $this->printErrorMessage("testGet_assigned_classes", "wrong size of returned values"));
        $id1 = $res[0]['ClassID'];
        $this->assertNotEquals(0, $id1, $this->printErrorMessage("testGet_assigned_classes_names", "wrong returned class id"));

    }

    public function testGet_topics_record()
    {
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        /*INSERT INTO `TopicRecord` (`ID`, `TeacherID`, `Timestamp`, `Description`, `TopicID`, `SpecificClassID`) VALUES
(3, 1, '2019-12-02 07:00:00', 'Italy enters the first world war ', 1, 3),
(5, 1, '2019-12-02 11:00:00', 'The Scientific Revolution ', 1, 3),
(9, 2, '2019-12-01 23:00:00', 'Italy enters the first world war', 1, 2),
(10, 1, '2019-12-02 10:00:00', 'Fluid dynamics', 2, 1),
(12, 1, '2019-12-03 09:00:00', 'angular momentum', 2, 1),
(13, 7, '2019-12-01 23:00:00', 'Atoms', 4, 3),
(14, 7, '2019-12-01 23:00:00', 'Lagrangean Relaxation', 3, 3);*/
        $res = $teacher->get_topics_record();
        if ($res == false)
            $this->fail($this->printErrorMessage("testGet_topics_record", "function returned false instead of an array"));
        $this->assertNotEquals(0, sizeof($res), $this->printErrorMessage("testGet_topics_record", "wrong size of returned array"));
        $this->assertTrue(sizeof($res) >= 4, $this->printErrorMessage("testGet_topics_record", "wrong size of returned array"));
        $_SESSION['teacherID'] = 3;
        $res = $teacher->get_topics_record();

        $this->assertFalse($res, $this->printErrorMessage("testGet_topics_record", "wrong size of returned array"));
    }

    public function testGet_lecture_by_ID()
    {
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $topicID = 9999;
        $res = $teacher->get_lecture_by_id($topicID);
        if ($res == false) {
            $this->fail($this->printErrorMessage("testGet_lecture_by_id", "function returned false instead of an array"));
        }
        $this->assertEquals(6, sizeof($res), $this->printErrorMessage("testGet_lecture_by_id", "wrong size of returned array"));
        $topicID = -1;
        $res = $teacher->get_lecture_by_id($topicID);
        $this->assertFalse($res, $this->printErrorMessage("testGet_lecture_by_id", "wrong size of returned array"));
    }

    public function testInsert_grade()
    {
        $_SESSION['teacherID'] = 1;
        $teacherObject = new Teacher();
        $studentID = 1;
        $subjectID = 1;
        $mark = -5;
        $laude = false;
        $timestamp = date("Y-m-d H:i:s");
//		$timestamp =time();

        // wrong value for mark
        $this->assertFalse($teacherObject->insert_grade($studentID, $subjectID, $mark, $laude, $timestamp));

        $mark = 10;
        $laude = 15;
        //wrong value for laude
        $this->assertFalse($teacherObject->insert_grade($studentID, $subjectID, $mark, $laude, $timestamp));

        $mark = 4;
        $laude = true;
        // wrong combination of mark laude
        $this->assertFalse($teacherObject->insert_grade($studentID, $subjectID, $mark, $laude, $timestamp));

        $timestamp = date('Y-m-d H:i:s', strtotime("2011-01-07"));
        $mark = 9;
        $laude = false;
        $this->assertTrue($teacherObject->insert_grade($studentID, $subjectID, $mark, $laude, $timestamp));
        $count = perform_SELECT_return_single_value("SELECT COUNT(*) FROM MarksRecord WHERE StudentID =$studentID AND Timestamp='$timestamp'");
        $this->assertEquals(1, $count, "Test non superato!");

        $timestamp = date('Y-m-d H:i:s', strtotime("2011-01-10"));
        $mark = 10;
        $laude = true;
        $this->assertNotNull($teacherObject->insert_grade($studentID, $subjectID, $mark, $laude, $timestamp));
        $count = perform_SELECT_return_single_value("SELECT COUNT(*) FROM MarksRecord WHERE StudentID =$studentID AND Timestamp='$timestamp'");
        $this->assertEquals(1, $count, "Test non superato!");
    }

    public function testInsert_new_assignment()
    {
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
        //$this->assertNotNull($teacherObject->insert_new_assignment($description, $topicID, $dateActualDate, $classID));
        $this->assertTrue($teacherObject->insert_new_assignment($description, $topicID, $dateActualDate, $classID));
        //$AssignmentID = perform_SELECT_return_single_value("SELECT ID FROM Homework WHERE Deadline ='$dateActualDate';");
        //printf("AssignmentID: %d\n",$AssignmentID);

        //$count = perform_SELECT_return_single_value("SELECT COUNT(*) FROM Homework WHERE ID =$AssignmentID");
        //printf("Count: %d\n",$count);

        //$this->assertEquals($count, 1, "Test non superato!");

        //$this->assertTrue(perform_INSERT_or_DELETE("DELETE FROM TopicRecord WHERE ID=$AssignmentID"));

        //Prints for debug
        //printf("TeacherID: %d\nDateActual: %s\nDescription: %s\nTopicID: %d\nSpecifiClassID: %d",$teacherID,$dateActualDate,$description,$topicID,$specificClassID);

    }

    public function testRegister_early_exit()
    {
        $_SESSION['teacherID'] = 1;
        $studentID = 1;
        $date = date("Y-m-d H:i:s");
        $teacher = new teacher();
        $res = $teacher->register_early_exit($studentID, $date, 4);
        $this->assertTrue($res, $this->printErrorMessage("testRegister_early_exit", "function should have returned true"));
        $timestamp = date("Y-m-d H:i:s", mktime(9, 00, 00, 11, 30, 2019));
        $res = $teacher->register_early_exit($studentID, $timestamp, 4);
        $this->assertFalse($res, $this->printErrorMessage("testRegister_early_exit", "function should have returned false"));
    }

    public function testRegister_early_exit_BOUNDARY()
    {
        $_SESSION['teacherID'] = 1;
        $studentID = 1;
        $date = date("Y-m-d H:i:s");
        $teacher = new teacher();
        $this->assertFalse($teacher->register_early_exit($studentID, $date, 10), $this->printErrorMessage("testRegister_early_exit", "function should have returned false"));
        $this->assertFalse($teacher->register_early_exit($studentID, null, 5), $this->printErrorMessage("testRegister_early_exit", "function should have returned false"));
        $this->assertFalse($teacher->register_early_exit(null, $date, 10), $this->printErrorMessage("testRegister_early_exit", "function should have returned false"));
        $this->assertFalse($teacher->register_early_exit($studentID, $date, null), $this->printErrorMessage("testRegister_early_exit", "function should have returned false"));
        $this->assertFalse($teacher->register_early_exit(null, null, 10), $this->printErrorMessage("testRegister_early_exit", "function should have returned false"));
        $this->assertFalse($teacher->register_early_exit(null, $date, null), $this->printErrorMessage("testRegister_early_exit", "function should have returned false"));
        $this->assertFalse($teacher->register_early_exit($studentID, null, null), $this->printErrorMessage("testRegister_early_exit", "function should have returned false"));
        $this->assertFalse($teacher->register_early_exit(null, null, null), $this->printErrorMessage("testRegister_early_exit", "function should have returned false"));

    }

    public function testRegister_absence()
    {
        $_SESSION["teacherID"] = 1;
        $teacherObject = new Teacher();
        $dateActualDate = date("Y-m-d H:i:s");
        $studentID = 1;

        // Wrong date
        $this->assertFalse($teacherObject->register_absence($studentID, -1));

        // Wrong studentID
        $this->assertFalse($teacherObject->register_absence(-1, $dateActualDate));

        // Student not in the class teached by teacher in object
        $this->assertFalse($teacherObject->register_absence(11, $dateActualDate));

        // True values
        $this->assertTrue($teacherObject->register_absence($studentID, $dateActualDate));

    }

    public function testStudent_was_absent()
    {
        $_SESSION["teacherID"] = 1;
        $teacherObject = new Teacher();
        $dateActualDate = date("Y-m-d");
        $dateActualDate2 = date("Y-m-d H:i:s");
        $studentID = 3;


        // Wrong student id
        $this->assertEquals(false, $teacherObject->student_was_absent($dateActualDate, -1), $this->printErrorMessage("testStudent_was_absent", "function should have returned false"));

        // Wrong date
        $this->assertEquals(false, $teacherObject->student_was_absent(-1, $studentID));

        // Student was not absent
        $this->assertFalse($teacherObject->student_was_absent($dateActualDate, $studentID));

        $this->assertTrue($teacherObject->register_absence($studentID, $dateActualDate2));

        // True values
        $this->assertTrue($teacherObject->student_was_absent($dateActualDate, $studentID));
    }

    public function testIs_teacher_of_the_student()
    {
        $_SESSION["teacherID"] = 1;
        $teacherObject = new teacher();
        $studentID = 1;

        // Wrong student id
        $this->assertEquals(false, $teacherObject->is_teacher_of_the_student(-1), $this->printErrorMessage("testIs_teacher_of_the_student", "function should have returned false"));

        // True value
        $this->assertEquals($studentID, $teacherObject->is_teacher_of_the_student($studentID), $this->printErrorMessage("testIs_teacher_of_the_student", "function should have returned 1"));

    }

    public function test_register_late_arrival()
    {
        /*INSERT INTO `NotPresentRecord` (`ID`, `StudentID`, `SpecificClassID`, `Date`, `Late`, `ExitHour`) VALUES
(1, 1, 1, '2019-11-28', 1, 4),
(2, 2, 1, '2019-11-28', 0, 0),
(3, 3, 1, '2019-11-28', 0, 4),
(4, 2, 1, '2020-01-08', 0, 0),
(5, 2, 1, '2020-01-09', 1, 5);
*/
        $_SESSION["teacherID"] = 1;
        $studentID = 2;
        $teacher = new teacher();
        $timestamp = date("Y-m-d H:i:s"/*, mktime(9, 00, 00, 11, 28, 2019)*/);
        $this->assertTrue($teacher->register_late_arrival($studentID, $timestamp));
        $timestamp = date("Y-m-d H:i:s", mktime(9, 00, 00, 11, 30, 2019));
        $this->assertFalse($teacher->register_late_arrival($studentID, $timestamp));
        $this->assertFalse($teacher->register_late_arrival($studentID, null));
        $this->assertFalse($teacher->register_late_arrival(null, $timestamp));
    }

    public function testIs_logged()
    {
        $_SESSION['teacherID'] = 1;
        $_SESSION['id'] = 1;
        $teacher = new teacher();
        $this->assertTrue($teacher->is_logged(), $this->printErrorMessage("testIs_logged", "teacher should be logged"));
        unset($_SESSION['teacherID']);
        $this->assertFalse($teacher->is_logged(), $this->printErrorMessage("testIs_logged", "teacher should not be logged"));
        $_SESSION['teacherID'] = -1;
        $this->assertFalse($teacher->is_logged(), $this->printErrorMessage("testIs_logged", "teacher should not be logged"));
        unset($_SESSION['id']);
        $this->assertFalse($teacher->is_logged(), $this->printErrorMessage("testIs_logged", "teacher should not be logged"));
        $_SESSION['teacherID'] = 1;
        $this->assertFalse($teacher->is_logged(), $this->printErrorMessage("testIs_logged", "teacher should not be logged"));
    }

    public function testGet_uploaded_material()
    {
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $res = $teacher->get_uploaded_material();
        if (!$res)
            $this->fail("Returned value should be an array , not a boolean value equals to false");
        $this->assertTrue(sizeof($res) > 0, $this->printErrorMessage("testGet_uploaded_material", "size of the returned array should be > than 0"));

        $_SESSION['teacherID'] = -2;
        $teacher = new teacher();
        $res = $teacher->get_uploaded_material();
        $this->assertTrue(sizeof($res) == 0, $this->printErrorMessage("testGet_uploaded_material", "size of returned array should be == 0"));

    }

    public function testInsert_material()
    {
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $fname = "testfile.txt";
        $serverName = "testservname";
        $class = 1;
        $descr = "test Description";
        $subject = 1;
        $res = $teacher->insert_material($fname, $serverName, $class, $descr, $subject);
        $this->assertTrue($res, $this->printErrorMessage("testInsert_material", "operation should have returned true"));
    }

    public function testRegister_note_record()
    {
        $noteID = 16;
        $studentID = 5;
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $res = $teacher->register_note_record($studentID, $noteID);
        $this->assertTrue($res, $this->printErrorMessage("testRegister_note_record", "operation should have returned true"));

        $res = $teacher->register_note_record(null, $noteID);
        $this->assertFalse($res, $this->printErrorMessage("testRegister_note_record", "operation should have returned falsee"));

        $res = $teacher->register_note_record($studentID, null);
        $this->assertFalse($res, $this->printErrorMessage("testRegister_note_record", "operation should have returned false"));

        $res = $teacher->register_note_record(null, null);
        $this->assertFalse($res, $this->printErrorMessage("testRegister_note_record", "operation should have returned false"));
    }

    public function testRegister_new_note()
    {
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $today = date("Y-m-d H:i:s");
        $classID = 1;
        $note = "TestNote 1";
        $res = $teacher->register_new_note($today, $classID, $note);
        printf("%d", $res);
        $this->assertTrue($res > 0, $this->printErrorMessage("testRegister_new_note", "operation should have returned true"));

        $res = $teacher->register_new_note(null, $classID, $note);
        $this->assertFalse($res, $this->printErrorMessage("testRegister_new_note", "operation should have returned false"));

        $res = $teacher->register_new_note($today, null, $note);
        $this->assertFalse($res, $this->printErrorMessage("testRegister_new_note", "operation should have returned false"));

        $res = $teacher->register_new_note($today, $classID, null);
        $this->assertFalse($res, $this->printErrorMessage("testRegister_new_note", "operation should have returned false"));

        $res = $teacher->register_new_note(null, null, $note);
        $this->assertFalse($res, $this->printErrorMessage("testRegister_new_note", "operation should have returned false"));

        $res = $teacher->register_new_note(null, $classID, null);
        $this->assertFalse($res, $this->printErrorMessage("testRegister_new_note", "operation should have returned false"));

        $res = $teacher->register_new_note($today, null, null);
        $this->assertFalse($res, $this->printErrorMessage("testRegister_new_note", "operation should have returned false"));

        $res = $teacher->register_new_note(null, null, null);
        $this->assertFalse($res, $this->printErrorMessage("testRegister_new_note", "operation should have returned false"));

        $invalidDate1 = date("Y/m/d");
        $invalidDate = date("Y-m-d H:i:s", strtotime("-8 days"));
        $invalidDate2 = date("Y-m-d H:i:s", strtotime("+1 days"));
        $res = $teacher->register_new_note($invalidDate, $classID, $note);
        $this->assertFalse($res, $this->printErrorMessage("testRegister_new_note", "operation should have returned false"));
        $res = $teacher->register_new_note($invalidDate1, $classID, $note);
        $this->assertFalse($res, $this->printErrorMessage("testRegister_new_note", "operation should have returned false"));
        $res = $teacher->register_new_note($invalidDate2, $classID, $note);
        $this->assertFalse($res, $this->printErrorMessage("testRegister_new_note", "operation should have returned false"));
    }

    public function testGet_Availability(){
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $res = $teacher->get_availability();
        $this->assertTrue($res != null);
        $this->assertNotEmpty($res);
    }

    public function testGet_booked_meetings(){
        $_SESSION['teacherID'] = 9999;
        $teacher = new teacher();
        $date = "2020-01-15";
        $teacherAvailabilityID = 9999;

        $res = $teacher->get_booked_meetings($date,$teacherAvailabilityID);
        $this->assertEquals(1,$res);

        $res = $teacher->get_booked_meetings($date,null);
        $this->assertFalse($res);

        $res = $teacher->get_booked_meetings(null,$teacherAvailabilityID);
        $this->assertFalse($res);
    }

    public function testGet_coordinated_class()
    {
        $specificclassID = 1;
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $res = $teacher->get_coordinated_class($teacher->get_teacher_ID());
        $this->assertEquals(intval($specificclassID), intval($res[0]['ID']));

        $res = $teacher->get_coordinated_class(null);
        $this->assertEmpty($res);

        $_SESSION['teacherID'] = 4;
        $teacher = new teacher();
        $res = $teacher->get_coordinated_class($teacher->get_teacher_ID());
        $this->assertEmpty($res);


    }

    public function testHas_final_grades()
    {
        //($studentID,$termID,$specificClassID)
        $specificclassID = 1;
        $termID=1;
        $studentID=8888;
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $this->assertFalse($teacher->has_final_grades($studentID,$termID,$specificclassID));

        $studentID=9999;
        $this->assertTrue($teacher->has_final_grades($studentID,$termID,$specificclassID));
    }

    public function testGet_actual_term()
    {
        $termID=1;
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $this->assertEquals($termID,$teacher->get_actual_term());

    }

    public function testGet_missing_term_marks()
    {
        $termID=1;
        $studentID=2;
        $specificclassid=1;
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $this->assertNotEmpty($teacher->get_missing_term_marks($studentID,$termID,$specificclassid));

        $studentID=1;
        $this->assertNotEmpty($teacher->get_missing_term_marks($studentID,$termID,$specificclassid));

    }

    public function testGet_student_stamp_by_id()
    {
        $studentID=1;
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $this->assertEquals("Lozano Hirving", $teacher->get_student_stamp_by_id($studentID));

        $studentID=24;
        $this->assertFalse($teacher->get_student_stamp_by_id($studentID));

    }

    public function testGet_average_mark_for_topic()
    {
        $studentID=6;
        $topicID=1;
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $this->assertEquals(8.5, $teacher->get_average_mark_for_topic($topicID,$studentID));

        $studentID=24;
        $this->assertEquals(0,$teacher->get_average_mark_for_topic($topicID,$studentID));
    }

    public function testSet_final_grade()
    {
        //public function set_final_grade($value){
        //$value=StudentID_TopicID_Grade_TermID

        $value="1_3_5_1";
        $classID=1;
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $this->assertEquals($classID, $teacher->set_final_grade($value));

        $value="1__5_1";
        $this->assertFalse($teacher->set_final_grade($value));

    }

    public function testGet_specificclassid_by_student()
    {
        $classID=1;
        $studentID=1;
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $this->assertEquals($classID, $teacher->get_specificclassid_by_student($studentID));

        $studentID=2;
        $this->assertEquals($classID, $teacher->get_specificclassid_by_student($studentID));

        $classID=2;
        $studentID=9;
        $this->assertEquals($classID, $teacher->get_specificclassid_by_student($studentID));

        $studentID=24;
        $this->assertFalse($teacher->get_specificclassid_by_student($studentID));
    }

    public function testGet_students_by_class_id2()
    {
        $classID=1;
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $this->assertCount(8,$teacher->get_students_by_class_id($classID));

        $classID=2;
        $this->assertCount(3,$teacher->get_students_by_class_id($classID));

        $classID=3;
        $this->assertCount(1,$teacher->get_students_by_class_id($classID));

        $classID=4;
        $this->assertCount(0,$teacher->get_students_by_class_id($classID));

    }

    public function testAdd_availability()
    {
        unset($_SESSION);
        $teacher = new teacher();
        $this->assertEquals(-1, $teacher->add_availability("no_day","no_hour"));
        $this->assertEquals(-1, $teacher->add_availability("Saturday","15:00"));
        $this->assertEquals(-1, $teacher->add_availability(-1,-1));
        $this->assertEquals(-3, $teacher->add_availability("Monday","08:00"));
        $_SESSION['teacherID'] = 1; // Marco Torchiano (userID=3)
        $this->assertEquals(-4, $teacher->add_availability("Monday","08:00"));
        $this->assertEquals(-4, $teacher->add_availability("Monday","10:00"));
        $this->assertEquals(1, $teacher->add_availability("Monday","12:00"));
    }


}
