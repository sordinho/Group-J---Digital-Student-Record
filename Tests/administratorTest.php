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

    /*public function test_register_new_user_parent(){
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
    }*/

    public function test_register_new_user_officer(){
        $admin = new administrator();
        $user_cf = "ned1";
        $user_name = "Ned";
        $user_surname = "Stark";
        $user_usergroup = "officer";
        $user_email = "king@inthe.grave";
        $res = $admin->register_new_user($user_name,$user_surname,$user_email,$user_usergroup,$user_cf);
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
        $user_cf = "jam1";
        $user_name = "Jamie";
        $user_surname = "Lannister";
        $user_usergroup = "teacher";
        $user_email = "king@slay.er";
        $res = $admin->register_new_user($user_name,$user_surname,$user_email,$user_usergroup,$user_cf);
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
        $user_cf = "jon1";
        $user_name = "Jon";
        $user_surname = "Snow";
        $user_usergroup = "parent";
        $user_email = "king@inthe.north";
        $res = $admin->register_new_user($user_name,$user_surname,null,$user_usergroup,$user_cf);
        $this->assertFalse($res,$this->printErrorMessage("test_register_new_user","wrong returned value"));

        $res = $admin->register_new_user(null,$user_surname,$user_email,$user_usergroup,$user_cf);
        $this->assertFalse($res,$this->printErrorMessage("test_register_new_user","wrong returned value"));

        $res = $admin->register_new_user($user_name,null,$user_email,$user_usergroup,$user_cf);
        $this->assertFalse($res,$this->printErrorMessage("test_register_new_user","wrong returned value"));

        $res = $admin->register_new_user($user_name,$user_surname,$user_email,null,$user_cf);
        $this->assertFalse($res,$this->printErrorMessage("test_register_new_user","wrong returned value"));

        $res = $admin->register_new_user($user_name,$user_surname,$user_email,$user_usergroup,null);
        $this->assertFalse($res,$this->printErrorMessage("test_register_new_user","wrong returned value"));

        $res = $admin->register_new_user($user_name,$user_surname,$user_email,$user_usergroup,$user_cf);
        $this->assertTrue($res,$this->printErrorMessage("test_register_new_user","wrong returned value"));
    }

}
