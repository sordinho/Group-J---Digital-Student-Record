<?php

use PHPUnit\Framework\TestCase;
require_once 'testUtility.php';
require_once "../classes/user.class.php";
require_once "../classes/sparent.class.php";

class sparentTest extends TestCase
{
    private function printErrorMessage($testName,$optionalMsg){
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
        $_SESSION['parentID'] = 1;
        $parent = new sparent();
        //parent_id 1 ----> child_id 1
        $grades = $parent->get_grades(1);
        $this->assertEquals(2,count($grades),$this->printErrorMessage("testGet_grades","grades[] should have length == 2"));
        $this->assertEquals("topic5",$grades[0]['Name'],$this->printErrorMessage("testGet_grades","line 51"));
        $this->assertEquals(2,$grades[0]['Mark'],$this->printErrorMessage("testGet_grades","line 52"));
        $this->assertEquals('2019-11-09 12:00:00',$grades[0]['Timestamp'],$this->printErrorMessage("testGet_grades","line 53"));
        $this->assertEquals("TeacherSur5",$grades[0]["Surname"],$this->printErrorMessage("testGet_grades","line 54"));
        $this->assertEquals("topic6",$grades[1]['Name'],$this->printErrorMessage("testGet_grades","line 55"));
        $this->assertEquals(5,$grades[1]['Mark'],$this->printErrorMessage("testGet_grades","line 56"));
        $this->assertEquals('2019-11-09 13:00:00',$grades[1]['Timestamp'],$this->printErrorMessage("testGet_grades","line 57"));
        $this->assertEquals("TeacherSur6",$grades[1]["Surname"],$this->printErrorMessage("testGet_grades","line 58"));
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
        $this->assertEquals(1, $parentObj->getParentId(), $this->printErrorMessage("test__construct","parent object ID not equals to session ID"));
    }

    public function testRetrieve_and_register_childs()
    {
        $_SESSION["parentID"] = 1;
        $parentObj = new sparent();
        $parentObj->retrieve_and_register_childs();
        $this->assertEquals(1,$_SESSION['childsID'][0],$this->printErrorMessage("testRestrieve_and_register_childs","ERROR IN STUDENT ID"));
        /*
         * $this->assertEquals( 1 ,$_SESSION['childrenInfo'][1],"ERROR IN PARENT ID");
         *  TODO
         *
         *
         * */
    }
}
