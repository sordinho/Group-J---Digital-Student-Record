<?php


use PHPUnit\Framework\TestCase;
require_once 'testUtility.php';
require_once "../classes/administrator.class.php";
require_once "../classes/user.class.php";


class administratorTest extends TestCase
{
    private function printErrorMessage($testName,$optionalMsg){
        $toReturn ="administratorTest: error in ".$testName;
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

    public function test_register_new_user_parent(){
        $admin = new administrator();
        $user_info = array();
        $user_info['name'] = "Jon";
        $user_info['surname'] = "Snow";
        $user_info['usergroup'] = "parent";
        $user_info['email'] = "king@inthe.north";
        $res = $admin->register_new_user($user_info);
        $this->assertTrue($res,$this->printErrorMessage("test_register_new_user_parent","wrong returned value"));


        $conn = TestsConnectMySQL();
        $query = "SELECT * 
                  FROM Parent p, User u 
                  WHERE p.UserID = u.ID AND u.Name = 'Jon' AND u.Surname = 'Snow' AND u.Email = 'king@inthe.north';";
        $res = $conn->query($query);
        $this->assertEquals(1,$res->fetch_row(),$this->printErrorMessage("test_register_new_user_parent","there are no new entries in the parent table."));
        $res->close();
    }

    public function test_register_new_user_officer(){
        $admin = new administrator();
        $user_info = array();
        $user_info['name'] = "Ned";
        $user_info['surname'] = "Stark";
        $user_info['usergroup'] = "officer";
        $user_info['email'] = "king@inthe.grave";
        $res = $admin->register_new_user($user_info);
        $this->assertTrue($res,$this->printErrorMessage("test_register_new_user_officer","wrong returned value"));


        $conn = TestsConnectMySQL();
        $query = "SELECT * 
                  FROM Offier p, User u 
                  WHERE p.UserID = u.ID AND u.Name = 'Ned' AND u.Surname = 'Stark' AND u.Email = 'king@inthe.grave';";
        $res = $conn->query($query);
        $this->assertEquals(1,$res->fetch_row(),$this->printErrorMessage("test_register_new_user_officer","there are no new entries in the teacher table."));
        $res->close();
    }

    public function test_register_new_user_teacher(){
        $admin = new administrator();
        $user_info = array();
        $user_info['name'] = "Jamie";
        $user_info['surname'] = "Lannister";
        $user_info['usergroup'] = "teacher";
        $user_info['email'] = "king@slay.er";
        $res = $admin->register_new_user($user_info);
        $this->assertTrue($res,$this->printErrorMessage("test_register_new_user_teacher","wrong returned value"));


        $conn = TestsConnectMySQL();
        $query = "SELECT * 
                  FROM Teacher p, User u 
                  WHERE p.UserID = u.ID AND u.Name = 'Jamie' AND u.Surname = 'Lannister' AND u.Email = 'king@slay.er';";
        $res = $conn->query($query);
        $this->assertEquals(1,$res->fetch_row(),$this->printErrorMessage("test_register_new_user_teacher","there are no new entries in the teacher table."));
        $res->close();
    }

    public function test_register_new_user_BOUNDARY(){
        $admin = new administrator();
        $user_info = array();
        $user_info['name'] = "Jon";
        $user_info['surname'] = "Snow";
        $user_info['usergroup'] = "parent";
        $res = $admin->register_new_user($user_info);
        $this->assertFalse($res,$this->printErrorMessage("test_register_new_user","wrong returned value"));

        $user_info['email'] = "king@inthe.north";
        unset($user_info['name']);
        $res = $admin->register_new_user($user_info);
        $this->assertFalse($res,$this->printErrorMessage("test_register_new_user","wrong returned value"));

        $user_info['name'] = "Jon";
        unset($user_info['surname']);
        $res = $admin->register_new_user($user_info);
        $this->assertFalse($res,$this->printErrorMessage("test_register_new_user","wrong returned value"));

        $user_info['surname'] = "Snow";
        unset($user_info['usergroup']);
        $res = $admin->register_new_user($user_info);
        $this->assertFalse($res,$this->printErrorMessage("test_register_new_user","wrong returned value"));

        $user_info['usergroup'] = "parent";
        $res = $admin->register_new_user($user_info);
        $this->assertTrue($res,$this->printErrorMessage("test_register_new_user","wrong returned value"));
    }

}
