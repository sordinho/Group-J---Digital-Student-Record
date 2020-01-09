<?php

use PHPUnit\Framework\TestCase;
require_once 'testUtility.php';
require_once "../classes/user.class.php";
require_once "../classes/sparent.class.php";
require_once "../classes/calendar.class.php";
require_once "../classes/teacher.class.php";

class sparentTest extends TestCase
{
    private function printErrorMessage($testName,$optionalMsg=''){
        $toReturn ="sparentTest: error in ".$testName;
        if(isset($optionalMsg) && !empty($optionalMsg))
            $toReturn.=" : ".$optionalMsg;
        return $toReturn;
    }
    public static function setUpBeforeClass(): void
    {
        createTestDatabase();
    }
    public static function tearDownAfterClass(): void
    {
        dropTestDatabase();
    }

    public function testGet_grades_NULL()
    {
        $parentObj = new sparent();
        $this->assertSameSize($parentObj->get_grades(array()), array(),$this->printErrorMessage("testGet_grades_NULL","it should return an empty array"));
    }
    public function testGet_grades(){
        /*INSERT INTO `MarksRecord` (`ID`, `StudentID`, `Mark`, `TeacherID`, `TopicID`, `Timestamp`) VALUES
(1, 2, 7, 1, 1, '2019-11-09 07:00:00'),
(2, 2, 7, 2, 2, '2019-11-09 08:00:00'),
(3, 3, 4, 3, 3, '2019-11-09 09:00:00'),
(4, 4, 2, 4, 4, '2019-11-09 10:00:00'),
(5, 1, 2, 5, 5, '2019-11-09 11:00:00'),
(6, 1, 5, 6, 6, '2019-11-09 12:00:00'),
(7, 5, 9, 7, 7, '2019-11-09 13:00:00');

        grades[] --> 0 : TOPIC NAME (id = 6 ---> topic6, id = 5 ---> topic5 )
                 --> 1 : MARK
                 --> 2 : TIMESTAMP
                 --> 3 : TEACHER SURNAME (id = 5 ---> TeacherSur5, id = 6 ---> TeacherSur6 )
*/
        /*INSERT INTO `Topic` (`ID`, `Name`, `Description`) VALUES
(1, 'History', 'Subject Description 1'),
(2, 'Physics', 'Subject Description 2'),
(3, 'Maths', 'Subject Description 3'),
(4, 'Science', 'Subject Description 4'),
(5, 'Geography', 'Subject Description 5'),
(6, 'Art', 'Subject Description 6'),
(7, 'Music', 'Subject Description 7');

        INSERT INTO `MarksRecord` (`ID`, `StudentID`, `Mark`, `TeacherID`, `TopicID`, `Timestamp`, `Laude`) VALUES
(1, 2, 7, 1, 1, '2019-11-09 07:00:00', 0),
(2, 2, 7, 2, 2, '2019-11-09 08:00:00', 0),
(3, 3, 4, 3, 3, '2019-11-09 09:00:00', 0),
(4, 4, 2, 4, 4, '2019-11-09 10:00:00', 0),
(7, 5, 9, 7, 7, '2019-11-09 13:00:00', 0),
(8, 2, 3.2, 2, 1, '2019-11-26 13:35:07', 1),
(9, 2, 3.2, 2, 1, '2019-11-26 13:35:07', 1);
         * */
        $_SESSION['parentID'] = 1;
        $parent = new sparent();
        //parent_id 1 ----> child_id 1
        $grades = $parent->get_grades(2);
        $this->assertTrue(count($grades)>=2,$this->printErrorMessage("testGet_grades","grades[] should have length == 4"));
        $this->assertEquals("History",$grades[0]['Name'],$this->printErrorMessage("testGet_grades","line 68"));
        $this->assertEquals(7,$grades[0]['Mark'],$this->printErrorMessage("testGet_grades","line 69"));
        $this->assertEquals('2019-11-09 08:00:00',$grades[0]['Timestamp'],$this->printErrorMessage("testGet_grades","line 70"));
        $this->assertEquals("Torchiano",$grades[0]["Surname"],$this->printErrorMessage("testGet_grades","line 71"));
        $this->assertEquals("Physics",$grades[1]['Name'],$this->printErrorMessage("testGet_grades","line 72"));
        $this->assertEquals(7,$grades[1]['Mark'],$this->printErrorMessage("testGet_grades","line 73"));
        $this->assertEquals('2019-11-09 09:00:00',$grades[1]['Timestamp'],$this->printErrorMessage("testGet_grades","line 74"));
        $this->assertEquals("Montuschi",$grades[1]["Surname"],$this->printErrorMessage("testGet_grades","line 75"));
    }
    public function testSet_current_child()
    {
        $_SESSION["parentID"] = 1;
        $parentObj = new sparent();
        $parentObj->set_current_child(2);
        $this->assertEquals(2, $_SESSION["curChild"],$this->printErrorMessage("testSet_current_child",""));
    }
    public function testGet_current_child()
    {
        $_SESSION["curChild"] = 2;
        $parentObj = new sparent();
        $this->assertEquals(2, $parentObj->get_current_child(), $this->printErrorMessage("testGet_current_child",""));
    }
    public function test__construct()
    {
        $_SESSION["parentID"] = 1;
        $parentObj = new sparent();
        $this->assertEquals(1, $parentObj->get_parent_ID(), $this->printErrorMessage("test__construct","parent object ID not equals to session ID"));
    }
    public function testRetrieve_and_register_childs()
    {
        $_SESSION['parentID'] = 1;
        $_SESSION['id']=1;
        $parentObj = new sparent();
        $res = $parentObj->retrieve_and_register_childs();
        if(!$res)
            $this->fail($this->printErrorMessage("testRetrieve_and_register_childs","res should be an array"));
        /*INSERT INTO `Student` (`ID`, `Name`, `Surname`, `AverageLastSchool`, `CF`, `SpecificClassID`) VALUES
(1, 'Hirving', 'Lozano', 10, 'LGGLPM50L71Z356X', 1),
(2, 'Vittorio', 'Di Leo', 10, 'PHGKRF55P70E908R', 1),
(3, 'Emanuele', 'Munafo', 10, 'DLGLYL71H30E159S', 1),
(4, 'Davide', 'Sordi', 10, 'HYFWMS36B11A963E', 1),
(5, 'Francesco', 'Riba', 10, 'JFVYMM92P59A229O', 1),
(6, 'Riccardo', 'Mamone', 10, 'ZGSQPD62P61F443K', 1),
(8, 'Antonio', 'Santoro', 10, 'GHFNDJ51S10L730U', 1),
(9, 'Michael', 'Bing', 7, 'RRQDWW41C60G670Z', 2),
(11, 'Mario', 'Rossi', 7, 'LVMLVS80T70L552B', 2),
(12, 'Javier', 'Lautaro', 10, 'LTRJVR97A01F839O', 2),
(13, 'Dries', 'Mertens', 10, 'MRTDRS89L03F839J', 3),
(18, 'Francesco', 'Riba', 9, 'WTCPGG93M51H398P', 1),
(23, 'Ross', 'Trebbiani', 9.25, 'TRBRSS80A01F839Q', -1);

        INSERT INTO `Parent` (`ID`, `StudentID`, `UserID`) VALUES
(2, 2, 2),
(5, 4, 1),
(7, 3, 45),
(8, 3, 44),
(10, 4, 2),
(11, 12, 55),
(12, 13, 56),
(14, 12, 1);*/

        $this->assertEquals($res->num_rows,sizeof($_SESSION['childrenInfo']),$this->printErrorMessage("testRetrieve_and_register_childs","sizes of returned value and value in session should be equals"));
        $this->assertEquals("Davide",$_SESSION['childrenInfo'][0]['Name'],$this->printErrorMessage("testRetrieve_and_register_childs","wrong student name"));
        $this->assertEquals("Sordi",$_SESSION['childrenInfo'][0]['Surname'],$this->printErrorMessage("testRetrieve_and_register_childs","wrong student surname"));
        $this->assertEquals(4,$_SESSION['childrenInfo'][0]['StudentID'],$this->printErrorMessage("testRetrieve_and_register_childs","wrong student id"));
    }
    public function testGet_homeworks() {
        $parentObj = new sparent();
        perform_INSERT_or_DELETE("DELETE FROM Homework");
        perform_INSERT_or_DELETE("INSERT INTO Homework(Description, SpecificClassID, TeacherID, Deadline, TopicID) VALUES('test',1,1,'2020-01-08',1)");
        perform_INSERT_or_DELETE("INSERT INTO Student(Name, Surname, AverageLastSchool,CF, SpecificClassID) VALUES('test','test',9,'bab5',1)");

        $studentID = perform_SELECT_return_single_value("SELECT ID FROM Student WHERE Name = 'test' AND Surname = 'test' AND AverageLastSchool = 9 AND CF = 'bab5' AND SpecificClassID = 1");

        $homework_info = $parentObj->get_homeworks($studentID);

        $this->assertEquals('test', $homework_info[0]['HomeworkDescription'], $this->printErrorMessage('testGet_homeworks'));
        $this->assertEquals('2020-01-08', $homework_info[0]['HomeworkDeadline'], $this->printErrorMessage('testGet_homeworks'));
        $this->assertTrue($homework_info[0]['HomeworkID'] != null, $this->printErrorMessage('testGet_homeworks'));
    }
    public function testGet_absences_and_delays(){
        $parentObj = new sparent();

        perform_INSERT_or_DELETE("INSERT INTO Student (Name, Surname, AverageLastSchool, CF, SpecificClassID) VALUES ('testName', 'testSurname', 10, 'testCF', 1)");
        $childID = perform_SELECT_return_single_value("SELECT ID FROM Student WHERE Name = 'testName' AND Surname = 'testSurname'");
        perform_INSERT_or_DELETE("INSERT INTO NotPresentRecord (StudentID, SpecificClassID, Date, Late, ExitHour) VALUES ($childID, 1, '2019-11-29', 1, 4)");
        perform_INSERT_or_DELETE("INSERT INTO NotPresentRecord (StudentID, SpecificClassID, Date, Late, ExitHour) VALUES ($childID, 1, '2019-12-03', 0, 0)");
        perform_INSERT_or_DELETE("INSERT INTO NotPresentRecord (StudentID, SpecificClassID, Date, Late, ExitHour) VALUES ($childID, 1, '2019-12-04', 1, 6)");
        $this->assertEmpty($parentObj->get_absences_and_delays(99));
        $absences = $parentObj->get_absences_and_delays(intval($childID));
        $this->assertEquals("2019-11-29", $absences[0]['Date']);
        $this->assertEquals(1, $absences[0]['Late']);
        $this->assertEquals(4, $absences[0]['ExitHour']);
        $this->assertEquals("2019-12-03", $absences[1]['Date']);
        $this->assertEquals(0, $absences[1]['Late']);
        $this->assertEquals(0, $absences[1]['ExitHour']);
        $this->assertEquals("2019-12-04", $absences[2]['Date']);
        $this->assertEquals(1, $absences[2]['Late']);
        $this->assertEquals(6, $absences[2]['ExitHour']);

        perform_INSERT_or_DELETE("INSERT INTO Student (Name, Surname, AverageLastSchool, CF, SpecificClassID) VALUES ('testName2', 'testSurname2', 10, 'testCF2', 1)");
        $childID = perform_SELECT_return_single_value("SELECT ID FROM Student WHERE Name = 'testName2' AND Surname = 'testSurname2'");
        perform_INSERT_or_DELETE("INSERT INTO NotPresentRecord (StudentID, SpecificClassID, Date, Late, ExitHour) VALUES ($childID, 1, '2019-12-10', 0, 3)");
        perform_INSERT_or_DELETE("INSERT INTO NotPresentRecord (StudentID, SpecificClassID, Date, Late, ExitHour) VALUES ($childID, 1, '2019-12-11', 0, 2)");
        perform_INSERT_or_DELETE("INSERT INTO NotPresentRecord (StudentID, SpecificClassID, Date, Late, ExitHour) VALUES ($childID, 1, '2019-12-12', 1, 6)");
        $absences = $parentObj->get_absences_and_delays(intval($childID), "2019-12-01", "2019-12-20");
        $this->assertEquals("2019-12-10", $absences[0]['Date']);
        $this->assertEquals(0, $absences[0]['Late']);
        $this->assertEquals(3, $absences[0]['ExitHour']);
        $this->assertEquals("2019-12-11", $absences[1]['Date']);
        $this->assertEquals(0, $absences[1]['Late']);
        $this->assertEquals(2, $absences[1]['ExitHour']);
        $this->assertEquals("2019-12-12", $absences[2]['Date']);
        $this->assertEquals(1, $absences[2]['Late']);
        $this->assertEquals(6, $absences[2]['ExitHour']);
        $absences = $parentObj->get_absences_and_delays(intval($childID), "2019-12-10", "2019-12-13");
        $this->assertEquals("2019-12-10", $absences[0]['Date']);
        $this->assertEquals(0, $absences[0]['Late']);
        $this->assertEquals(3, $absences[0]['ExitHour']);
        $this->assertEquals("2019-12-11", $absences[1]['Date']);
        $this->assertEquals(0, $absences[1]['Late']);
        $this->assertEquals(2, $absences[1]['ExitHour']);
        $this->assertEquals("2019-12-12", $absences[2]['Date']);
        $this->assertEquals(1, $absences[2]['Late']);
        $this->assertEquals(6, $absences[2]['ExitHour']);
        $absences = $parentObj->get_absences_and_delays(intval($childID), "2019-12-01", "2019-12-02");
        $this->assertEmpty($absences);
        $this->assertFalse($parentObj->get_absences_and_delays(intval($childID), "20"));
    }
    public function testGet_absences() {
        $parentObj = new sparent();

        perform_INSERT_or_DELETE("INSERT INTO Student (Name, Surname, AverageLastSchool, CF, SpecificClassID) VALUES ('testName', 'testSurname', 10, 'testCF', 1)");
        $childID = perform_SELECT_return_single_value("SELECT ID FROM Student WHERE Name = 'testName' AND Surname = 'testSurname'");
        perform_INSERT_or_DELETE("INSERT INTO NotPresentRecord (StudentID, SpecificClassID, Date, Late, ExitHour) VALUES ($childID, 1, '2019-11-29', 1, 4)");
        perform_INSERT_or_DELETE("INSERT INTO NotPresentRecord (StudentID, SpecificClassID, Date, Late, ExitHour) VALUES ($childID, 1, '2019-12-03', 0, 0)");
        perform_INSERT_or_DELETE("INSERT INTO NotPresentRecord (StudentID, SpecificClassID, Date, Late, ExitHour) VALUES ($childID, 1, '2019-12-04', 0, 0)");
        $this->assertEmpty($parentObj->get_absences(99));
        $absences = $parentObj->get_absences(intval($childID));
        $this->assertEquals("2019-12-03", $absences[0]['Date']);
        $this->assertEquals("2019-12-03", $absences[1]['Date']);

        perform_INSERT_or_DELETE("INSERT INTO Student (Name, Surname, AverageLastSchool, CF, SpecificClassID) VALUES ('testName2', 'testSurname2', 10, 'testCF2', 1)");
        $childID = perform_SELECT_return_single_value("SELECT ID FROM Student WHERE Name = 'testName2' AND Surname = 'testSurname2'");
        perform_INSERT_or_DELETE("INSERT INTO NotPresentRecord (StudentID, SpecificClassID, Date, Late, ExitHour) VALUES ($childID, 1, '2019-12-10', 0, 0)");
        perform_INSERT_or_DELETE("INSERT INTO NotPresentRecord (StudentID, SpecificClassID, Date, Late, ExitHour) VALUES ($childID, 1, '2019-12-11', 0, 0)");
        perform_INSERT_or_DELETE("INSERT INTO NotPresentRecord (StudentID, SpecificClassID, Date, Late, ExitHour) VALUES ($childID, 1, '2019-12-12', 0, 0)");
        $absences = $parentObj->get_absences(intval($childID), "2019-12-01", "2019-12-20");
        $this->assertEquals("2019-12-10", $absences[0]['Date']);
        $this->assertEquals("2019-12-11", $absences[1]['Date']);
        $this->assertEquals("2019-12-12", $absences[2]['Date']);
        $absences = $parentObj->get_absences(intval($childID), "2019-12-10", "2019-12-13");
        $this->assertEquals("2019-12-10", $absences[0]['Date']);
        $this->assertEquals("2019-12-11", $absences[1]['Date']);
        $this->assertEquals("2019-12-12", $absences[2]['Date']);
        $absences = $parentObj->get_absences(intval($childID), "2019-12-01", "2019-12-02");
        $this->assertEmpty($absences);
        $this->assertFalse($parentObj->get_absences(intval($childID), "20"));
        //$this->assertFalse($parentObj->get_absences(intval($childID), "2020-01-01"));
    }
    public function testGet_announcements(){
        /*INSERT INTO `Communication` (`ID`, `Title`, `Description`, `Timestamp`, `OfficerID`) VALUES
(1, 'Christmas holidays', 'All lectures are suspended from 20/12/2019 until 07/01/2020', '2019-12-09 16:34:14', 1),
(2, 'All labs will be closed', 'The access to all laboratories will be restored on 10/01/2020', '2019-12-09 17:34:14', 2),
(3, 'Lecture suspended', 'All lectures are suspended on 11/12/2019', '2019-12-09 16:35:07', 1);*/
        $_SESSION["parentID"] = 1;
        $parentObj = new sparent();
        $res = $parentObj->get_announcements(2);
        if(!$res)
            $this->fail($this->printErrorMessage("testGet_announcements","returned value should be an array"));
        $this->assertEquals(2,sizeof($res),$this->printErrorMessage("testGet_announcements","wrong size of returned array"));
        $res = $parentObj->get_announcements();
        if(!$res)
            $this->fail($this->printErrorMessage("testGet_announcements","returned value should be an array"));
        $this->assertTrue(sizeof($res)<=4,$this->printErrorMessage("testGet_announcements","wrong size of returned array"));
    }
    public function testGet_parent_ID(){
        $_SESSION['parentID'] = 2;
        $parent = new sparent();
        $this->assertEquals(2,$parent->get_parent_ID(),$this->printErrorMessage("testGet_parent_ID","wrong returned value"));
        unset($_SESSION['parentID']);
        $this->assertEquals(-1,$parent->get_parent_ID(),$this->printErrorMessage("testGet_parent_ID","wrong returned value"));
    }
    public function testGet_children_info(){
        $tmp = array();
        $childInfo = array();
        $childInfo['Name'] = "testNam";
        $childInfo['Surname'] = "TestSurn";
        $childInfo['StudentID'] = 1;
        $tmp[0] = $childInfo;
        $_SESSION['childrenInfo'] = $tmp;
        $_SESSION['parentID'] = 1;
        $parent = new sparent();
        $res = $parent->get_children_info();
        $this->assertEquals(1, sizeof($res),$this->printErrorMessage("testGet_children_info","wrong size of returned array"));
        $this->assertEquals("testNam",$res[0]['Name'],$this->printErrorMessage("testGet_children_info","wrong returned name"));
        $this->assertEquals("TestSurn",$res[0]['Surname'],$this->printErrorMessage("testGet_children_info","wrong returned surname"));
        $this->assertEquals(1,$res[0]['StudentID'],$this->printErrorMessage("testGet_children_info","wrong returned ID"));

        unset($_SESSION['childrenInfo']);
        $res = $parent->get_children_info();
        $this->assertEquals(1, sizeof($res),$this->printErrorMessage("testGet_children_info","wrong size of returned array"));
        $this->assertEquals("No children",$res[0]['Name'],$this->printErrorMessage("testGet_children_info","wrong returned name"));
        $this->assertEquals("Registered",$res[0]['Surname'],$this->printErrorMessage("testGet_children_info","wrong returned surname"));
        $this->assertEquals(-1,$res[0]['StudentID'],$this->printErrorMessage("testGet_children_info","wrong returned ID"));
    }
    public function testIs_logged(){
        $_SESSION['parentID'] = 1;
        $_SESSION['id'] = 1;
        $parent = new sparent();
        $this->assertTrue($parent->is_logged(),$this->printErrorMessage("testIs_logged","parent should be logged"));
        unset($_SESSION['parentID']);
        $this->assertFalse($parent->is_logged(),$this->printErrorMessage("testIs_logged","parent should not be logged"));
        $_SESSION['parentID'] = -1;
        $this->assertFalse($parent->is_logged(),$this->printErrorMessage("testIs_logged","parent should not be logged"));
        unset($_SESSION['id']);
        $this->assertFalse($parent->is_logged(),$this->printErrorMessage("testIs_logged","parent should not be logged"));
        $_SESSION['parentID'] = 1;
        $this->assertFalse($parent->is_logged(),$this->printErrorMessage("testIs_logged","parent should not be logged"));
    }
    public function testGet_child_stamp_by_id(){
        $_SESSION['parentID'] = 1;
        $parent = new sparent();
        $this->assertEquals(0, sizeof($parent->get_child_stamp_by_id(null)));
        $this->assertEquals("Vittorio Di Leo", $parent->get_child_stamp_by_id(2));
    }
    public function testGet_material_info(){
        $_SESSION['parentID'] = 1;
        $parent = new sparent();
        $res = $parent->get_material_info(1);
        if(!$res)
            $this->fail($this->printErrorMessage("testGet_material_info","returned value should be an array"));
        $this->assertTrue(sizeof($res)>0);
        $res = $parent->get_material_info(null);
        //if(!$res)
          //  $this->fail($this->printErrorMessage("testGet_material_info","returned value should be an array"));
        $this->assertTrue(sizeof($res)==0);
        $res = $parent->get_material_info(-3);
        $this->assertTrue(sizeof($res)==0);
    }
    public function testGet_num_unseen_notes(){
        $_SESSION['parentID'] = 2;
        $parent = new sparent();
        $_SESSION['unseenNotes'] = 2;
        $this->assertEquals(2,$parent->get_num_unseen_notes(-1));
        unset($_SESSION['unseenNotes']);
        $this->assertEquals(0,$parent->get_num_unseen_notes(-1));

        $_SESSION['unseenNotes_2'] = 3;
        $this->assertEquals(3,$parent->get_num_unseen_notes(2));
        unset($_SESSION['unseenNotes_2']);
        $this->assertEquals(0,$parent->get_num_unseen_notes(2));
    }
    public function testSet_current_num_unseen_notes(){
        $teacher = new teacher();
        $_SESSION['teacherID'] = 1;
        $teacher->register_note_record(2,1);
        $teacher->register_note_record(2,2);
        $_SESSION['parentID'] = 2;
        $parent = new sparent();
        $parent->set_current_num_unseen_notes(-1);
        $this->assertEquals(0,$_SESSION['unseenNotes_-1']);
        $parent->set_current_num_unseen_notes(2);
        $this->assertTrue($_SESSION['unseenNotes_2']>=2);
    }
    public function testGet_unseen_notes(){
        $_SESSION['parentID'] = 2;
        $_SESSION['id'] = 2;
        $parent = new sparent();
        $_SESSION['teacherID'] = 1;
        $teacher = new teacher();
        $teacher->register_note_record(4,1);
        $res = $parent->get_unseen_notes(-1);
        $this->assertTrue(sizeof($res)>=3);
        $res = $parent->get_unseen_notes(2);
        $this->assertTrue(sizeof($res)>=2);
        $res = $parent->get_unseen_notes(4);
        $this->assertTrue(sizeof($res)>=1);
        $res = $parent->get_unseen_notes(null);
        $this->assertTrue(sizeof($res)==0);
    }
    public function testGet_notes(){
        $_SESSION['parentID'] = 2;
        $_SESSION['id'] = 2;
        $parent = new sparent();
        $res = $parent->get_notes(-1);
        $this->assertTrue(sizeof($res)>=3);
        $res = $parent->get_notes(2);
        $this->assertTrue(sizeof($res)>=2);
        $res = $parent->get_notes(4);
        $this->assertTrue(sizeof($res)>=1);
        $res = $parent->get_notes(null);
        $this->assertTrue(sizeof($res)==0);
    }
    public function testSet_notes_seen(){
        $noteID = 1; // see testGet_unseen_notes
        $notes = array();
        $notes[0] = $noteID;
        $_SESSION['parentID']=2;
        $parent = new sparent();
        $this->assertFalse($parent->set_notes_seen(null));
        $this->assertTrue($parent->set_notes_seen($notes));
    }

    public function testGet_term_list(){

        $_SESSION['parentID']=2;
        $parent = new sparent();

        $this->assertNotEmpty($parent->get_term_list());
        //$this->assertFalse($parent->get_term_list());
    }

    public function testGet_term_stamp_by_id(){

        $stamp1="2019/2020 - 01";
        $stamp2="2018/2019 - 02";
        $_SESSION['parentID']=2;
        $parent = new sparent();

        $this->assertEquals($stamp1,$parent->get_term_stamp_by_id(1));
        $this->assertEquals($stamp2,$parent->get_term_stamp_by_id(2));
        $this->assertFalse($parent->get_term_stamp_by_id(0));
    }

    public function testGet_final_term_marks_by_studentID(){

        $studentID1=2;
        $studentID2=4;
        $studentID3=0;
        $termID1=1;
        $termID2=0;
        $_SESSION['parentID']=2;
        $parent = new sparent();

        $this->assertNotEmpty($parent->get_final_term_marks_by_studentID($studentID1,$termID1));
        $this->assertNotEmpty($parent->get_final_term_marks_by_studentID($studentID2,$termID1));
        $this->assertFalse($parent->get_final_term_marks_by_studentID($studentID3,$termID2));
    }

    public function testGet_teacher_availability(){
		$_SESSION['parentID']=2;
		$parent = new sparent();

		// No child for the parent
		$this->assertEquals(0,sizeof($parent->get_teacher_availability()));

		// Set children info in session
		$parent->set_current_child(2);
		$this->assertNotEquals(0,sizeof($parent->get_teacher_availability()));
	}

	public function testGet_future_reservations_by_teacher_availability_id(){
		$_SESSION['parentID']=2;
		$parent = new sparent();

		// Invalid teacher availability id
		$this->assertEquals(0,sizeof($parent->get_future_reservations_by_teacher_availability_id(-1)));

		// Teacher with no booked meetings
		$this->assertEquals(0,sizeof($parent->get_future_reservations_by_teacher_availability_id(3)));

		$datetime = new DateTime('tomorrow');
		$tomorrow = $datetime->format('Y-m-d');
		$queryInsert = "INSERT INTO MeetingReservation (ParentID,TeacherAvailabilityID,Date,Timeslot) VALUES (10,3,'".$tomorrow."',0)";
		perform_INSERT_or_DELETE($queryInsert);

		$this->assertEquals(1,sizeof($parent->get_future_reservations_by_teacher_availability_id(3)));
	}

	public function testBook_meeting(){
		$_SESSION['parentID']=2;
		$parent = new sparent();

		// invalid parent
		$this->assertFalse($parent->book_meeting(null,1,date("Y-m-d"),0));

		// invalid teacher
		$this->assertFalse($parent->book_meeting(2,null,date("Y-m-d"),0));

		// invalid date
		$this->assertFalse($parent->book_meeting(2,1,null,0));

		// invalid timeslot
		$this->assertFalse($parent->book_meeting(2,1,date("Y-m-d"),null));

		// Valid meeting
		$this->assertTrue($parent->book_meeting(2,1,"2020-01-13",0));
	}

}
