<?php

use PHPUnit\Framework\TestCase;
require_once 'testUtility.php';
require_once "../classes/user.class.php";
require_once "../classes/sparent.class.php";

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
        $this->assertEquals(4,count($grades),$this->printErrorMessage("testGet_grades","grades[] should have length == 4"));
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
        $_SESSION["parentID"] = 1;
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
        $_SESSION["parentID"] = 1;
        $parentObj = new sparent();
        $parentObj->retrieve_and_register_childs();
        $this->assertEquals(1,$_SESSION['childrenInfo']['ParentID'],$this->printErrorMessage("testRestrieve_and_register_childs","ERROR IN STUDENT ID"));
        /*
         * $this->assertEquals( 1 ,$_SESSION['childrenInfo'][1],"ERROR IN PARENT ID");
         *  TODO
         *
         *
         * */
    }

    public function testGet_homeworks() {
        $parentObj = new sparent();
        perform_INSERT_or_DELETE("DELETE FROM Homework");
        perform_INSERT_or_DELETE("INSERT INTO Homework(Description, SpecificClassID, TeacherID, Deadline) VALUES('test',1,1,'2020-01-08')");
        perform_INSERT_or_DELETE("INSERT INTO Student(Name, Surname, AverageLastSchool,CF, SpecificClassID) VALUES('test','test',9,'bab5',1)");

        $studentID = perform_SELECT_return_single_value("SELECT ID FROM Student WHERE Name = 'test' AND Surname = 'test' AND AverageLastSchool = 9 AND CF = 'bab5' AND SpecificClassID = 1");

        $homework_info = $parentObj->get_homeworks($studentID);

        $this->assertEquals('test', $homework_info[0]['HomeworkDescription'], $this->printErrorMessage('testGet_homeworks'));
        $this->assertEquals('2020-01-08', $homework_info[0]['HomeworkDeadline'], $this->printErrorMessage('testGet_homeworks'));
        $this->assertTrue($homework_info[0]['HomeworkID'] != null, $this->printErrorMessage('testGet_homeworks'));
    }


    public function testGet_absences() {
        $parentObj = new sparent();
        // 2	Joseph	ParentSurname2	pns2a@io.io	$2y$12$ZOB4hLXsBQmRWwU7u0hP4e3GUbyOEg7Gll1ZJMEDd4d4sWiqDE8by	parent
        // 2	Vittorio	Di Leo	10	cf1b	1
        $_SESSION['id'] = 2;
        $_SESSION['curChild'] = 2;
        perform_INSERT_or_DELETE("INSERT INTO NotPresentRecord (StudentID, SpecificClassID, Date, Late, ExitHour) VALUES (1, 1, '2019-11-28', 'Yes', '4')");
        perform_INSERT_or_DELETE("INSERT INTO NotPresentRecord (StudentID, SpecificClassID, Date, Late, ExitHour) VALUES (1, 1, '2019-11-29', 'No', '0')");
        perform_INSERT_or_DELETE("INSERT INTO NotPresentRecord (StudentID, SpecificClassID, Date, Late, ExitHour) VALUES (9, 2, '2019-12-02', 'No', '0')");
        // perform_INSERT_or_DELETE("INSERT INTO NotPresentRecord (StudentID, SpecificClassID, Date, Late, ExitHour) VALUES (2, 1, '2019-12-03', 'Yes', '0')");

        $this->assertTrue($parentObj->get_absences());
    }
}
