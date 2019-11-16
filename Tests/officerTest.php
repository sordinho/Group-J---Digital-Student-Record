<?php

use PHPUnit\Framework\TestCase;
require_once 'testUtility.php';
require_once "../classes/officer.class.php";
require_once "../classes/user.class.php";


class officerTest extends TestCase
{
    private function printErrorMessage($testName,$optionalMsg){
        $toReturn ="officerTest: error in ".$testName;
        if(isset($optionalMsg) && !empty($optionalMsg))
            $toReturn.=" --> ".$optionalMsg.".";
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
    public function testGetOfficerID(){
        $_SESSION['officerID'] = 1;
        $officer = new officer();
        $this->assertEquals(1,$officer->get_officer_ID(),$this->printErrorMessage("testGetOfficerID","wrong returned value"));
        unset($_SESSION['officerID']);
        $this->assertEquals(-1,$officer->get_officer_ID(),$this->printErrorMessage("testGetOfficerID","returned value should be -1"));
    }

    public function testEnrollStudent(){
        $student_info = array();
        $student_info['name'] = 'Jon';
        $student_info['surname'] = 'Snow';
        $student_info['avgLastSchool'] = 7.55;
        $student_info['CF'] = 'King_in_the_north';
        $officer = new officer();
        $res = $officer->enroll_student($student_info);

        $this->assertTrue($res,$this->printErrorMessage('testEnrollStudent','returned value should be true'));

        unset($student_info['name']);
        $res = $officer->enroll_student($student_info);
        $this->assertFalse($res,$this->printErrorMessage('testEnrollStudent','returned value should be false'));

        $student_info['name'] = 'Jon';
        unset($student_info['surname']);
        $res =$officer->enroll_student($student_info);
        $this->assertFalse($res,$this->printErrorMessage('testEnrollStudent','returned value should be false'));

        $student_info['surname'] = 'Snow';
        unset($student_info['avgLastSchool']);
        $res =$officer->enroll_student($student_info);
        $this->assertFalse($res,$this->printErrorMessage('testEnrollStudent','returned value should be false'));

        $student_info['avgLastSchool'] = 7.55;
        unset($student_info['CF']);
        $res =$officer->enroll_student($student_info);
        $this->assertFalse($res,$this->printErrorMessage('testEnrollStudent','returned value should be false'));
    }
    public function testAddNewUser(){
        $officer = new officer();
        $res = $officer->add_new_user(null,null,null);
        $this->assertEquals(-1,$res,$this->printErrorMessage("testAddNewUser","returned value should be -1"));
        $res = $officer->add_new_user(null,"SNOW",null);
        $this->assertEquals(-1,$res,$this->printErrorMessage("testAddNewUser","returned value should be -1"));
        $res = $officer->add_new_user("Jon",null,null);
        $this->assertEquals(-1,$res,$this->printErrorMessage("testAddNewUser","returned value should be -1"));
        $res = $officer->add_new_user(null,null,"kingIn@the.north");
        $this->assertEquals(-1,$res,$this->printErrorMessage("testAddNewUser","returned value should be -1"));
        $res = $officer->add_new_user("Jon","Snow",null);
        $this->assertEquals(-1,$res,$this->printErrorMessage("testAddNewUser","returned value should be -1"));
        $res = $officer->add_new_user(null,"Snow","kingIn@the.north");
        $this->assertEquals(-1,$res,$this->printErrorMessage("testAddNewUser","returned value should be -1"));
        $res = $officer->add_new_user("Jon",null,"kingIn@the.north");
        $this->assertEquals(-1,$res,$this->printErrorMessage("testAddNewUser","returned value should be -1"));

        $res = $officer->add_new_user("Jon","Snow","kingIn@the.north");
        $this->assertTrue($res>0,$this->printErrorMessage("testAddNewUser","returned value should not be -1"));
    }
    public function testAddNewParent(){
        $officer = new officer();
        $usr_id = 5;
        $child_info = array();
        $child_info['cf_0'] = 'cf1';
        $childN = 1;
        $res = $officer->add_new_parent($usr_id,$child_info,$childN);
        $this->assertTrue($res,$this->printErrorMessage('testAddNewParent','returned value should be true'));
        $child_info['cf_1'] = 'cf2';
        $childN++;
        $res = $officer->add_new_parent($usr_id,$child_info,$childN);
        $this->assertTrue($res,$this->printErrorMessage('testAddNewParent','returned value should be true'));
        $childN++;
        $res = $officer->add_new_parent($usr_id,$child_info,$childN);
        $this->assertFalse($res,$this->printErrorMessage('testAddNewParent','returned value should be false'));
        unset($child_info);
        $child_info = array();
        $res = $officer->add_new_parent($usr_id,$child_info,$childN);
        $this->assertFalse($res,$this->printErrorMessage('testAddNewParent','returned value should be false'));
    }
    public function testRegisterNewParent(){
        $name = 'Jon';
        $surname = 'Snow';
        $email = 'kingIn@the.north';
        $childinfo= array();
        $childinfo['cf_0']='cf1';
        $childN = 1;
        $officer = new officer();
        $res = $officer->register_new_parent($name,$surname,$email,$childinfo,$childN);
        $this->assertTrue($res);
    }
}
