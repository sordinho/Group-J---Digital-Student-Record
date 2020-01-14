<?php

require_once 'testUtility.php';

use PHPUnit\Framework\TestCase;

require_once "../classes/user.class.php";

class userTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        createTestDatabase();
    }

    public static function tearDownAfterClass(): void
    {
        dropTestDatabase();
    }

    public function testGet_name()
    {
        $_SESSION['name'] = "test";
        $user = new user();
        $this->assertEquals("test", $user->get_name(), "userTest : test_get_name wrong returned value");
    }

    public function testIs_secure_password()
    {
        $user = new user();
        $this->assertFalse($user->is_secure_password('cat'), 'is_secure_password accepts lenght(password) < 5');
        $this->assertTrue($user->is_secure_password('cover'), 'is_secure password not accepting lenght(password) == 5');
        $this->assertTrue($user->is_secure_password('elephant'), 'is_secure_password not accepting lenght(password) > 5');
    }

    public function testIs_email()
    {
        $user = new user();
        $this->assertEquals(1, $user->is_email('adamo@paradiso.it'), 'testIs_email failed: valid email is to be accepted');
        $this->assertEquals(0, $user->is_email('adamoparadiso.it'), 'testIs_email failed: non-valid email is not to be accepted');
        $this->assertEquals(0, $user->is_email('adamo@paradisoit'), 'testIs_email failed: non-valid email is not to be accepted');
        $this->assertEquals(0, $user->is_email('adamoparadisoit'), 'testIs_email failed: non-valid email is not to be accepted');
        $this->assertEquals(0, $user->is_email('@paradiso.it'), 'testIs_email failed: non-valid email is not to be accepted');
        $this->assertEquals(0, $user->is_email('@paradiso'), 'testIs_email failed: non-valid email is not to be accepted');
        $this->assertEquals(0, $user->is_email('adamo@'), 'testIs_email failed: non-valid email is not to be accepted');
    }

    public function testIs_logged()
    {
        $_SESSION['id'] = "test";
        $user = new user();
        $this->assertTrue($user->is_logged(), "userTest: testIs_logged function is_logged should return true.");
        unset($_SESSION['id']);
        $this->assertFalse($user->is_logged(), "userTest: testIs_logged function is_logged should return false.");
    }

    public function testGet_id()
    {
        $_SESSION['id'] = "1";
        $user = new user();
        $this->assertEquals("1", $user->get_id(), "userTest : testGet_id wrong returned value");
        unset($_SESSION['id']);
        $this->assertEquals(-1, $user->get_id(), "userTest : testGet_id wrong returned value");
    }

    public function testGet_base_url()
    {
        $_SESSION['base_url'] = "/test/";
        $user = new user();
        $this->assertEquals("/test/", $user->get_base_url(), "userTest : testGet_base_url wrong returned value");
        unset($_SESSION['base_url']);
        $this->assertEquals('', $user->get_base_url(), "userTest : testGet_base_url wrong returned value");
    }

    public function testGet_link_logout()
    {
        $_SESSION['id'] = '1';
        $user = new user();
        $this->assertEquals('<a href="' . $this->Urls['logout_page'] . '" class="logout">Logout</a>', $user->get_link_logout(), "userTest : testGet_link_logout wrong returned value");
        unset($_SESSION['id']);
        $this->assertEquals('', $user->get_link_logout(), "userTest : testGet_link_logout wrong returned value");
    }

    public function testGet_usergroup()
    {
        $_SESSION['usergroup'] = 'test';
        $user = new user();
        $this->assertEquals('test', $user->get_usergroup(), "userTest : get_usergroup wrong returned value");
        unset($_SESSION['usergroup']);
        $this->assertEquals('', $user->get_usergroup(), "userTest : get_usergroup wrong returned value");
    }

    public function testUser_login()
    {

        //TD : Modify - still not working
        $user_data = ['username' => 'email@test.test', 'password' => 'passwordtest'];
        $user = new user();
        $_SESSION['id'] = 73;
        $hashed_password = password_hash($user_data['password'], PASSWORD_DEFAULT, ['cost' => 12]);
        $query = "INSERT INTO User (Name, Surname, Email, Password, UserGroup) VALUES ('TestName', 'TestSurname', 'email@test.test', '$hashed_password', 'parent')";
        $this->assertTrue(perform_INSERT_or_DELETE($query), 'query failed');
        $userID = $user->get_id();
        $query2 = "INSERT INTO Parent (StudentID, UserID) VALUES (1,$userID)";
        $this->assertTrue(perform_INSERT_or_DELETE($query2), 'query failed');

        //correct username e password
        $this->assertGreaterThanOrEqual(1, $user->user_login($user_data), "userTest : testUser_login failed at login correctly");

        //password errata e utente corretto
        $user_data_incorrect1 = ['username' => 'email@test.test', 'password' => 'incorrectpasswordtest'];
        $this->assertLessThanOrEqual(0, $user->user_login($user_data_incorrect1), "userTest : testUser_login returned true, when it would had not");

        //password e utente scorretti
        $user_data_incorrect2 = $user_data_incorrect1;
        $user_data_incorrect2['username'] = 'incorrecttestemail';

        //duplicate username insertion
        perform_INSERT_or_DELETE($query);
        $this->assertEquals(2, $user->user_login($user_data), "userTest : testUser_login returned true, when it would had not");
    }

    public function testGet_surname()
    {
        $_SESSION['surname'] = "test";
        $user = new user();
        $this->assertEquals("test", $user->get_surname(), "userTest : testGet_surname wrong returned value");
        unset($_SESSION['surname']);
        $this->assertEquals('', $user->get_surname(), "userTest : testGet_surname wrong returned value");
    }

    public function testGet_username()
    {
        $_SESSION['username'] = "test";
        $user = new user();
        $this->assertEquals("test", $user->get_username(), "userTest : testGet_surname wrong returned value");
        unset($_SESSION['username']);
        $this->assertEquals('', $user->get_username(), "userTest : testGet_surname wrong returned value");
    }

    public function testRetrieve_usergroups()
    {
        $username1 = "marco.torchiano@io.io"; //Teacher
        $username2 = "tony.lioy@io.io"; //Teacher + parent

        $user1 = new user();

        $this->assertEquals(0, sizeof($user1->retrieve_usergroups(null)));
        $this->assertEquals(0, sizeof($user1->retrieve_usergroups($username1)));
        $this->assertEquals(2, sizeof($user1->retrieve_usergroups($username2)));
    }

    public function testRetrieve_user_id_by_usergroup()
    {
        $username1 = "marco.torchiano@io.io"; //Teacher
        $username2 = "tony.lioy@io.io"; //Teacher + parent

        $user1 = new user();

        $this->assertEquals(-1, $user1->retrieve_user_id_by_usergroup(null, null));
        $this->assertEquals(3, $user1->retrieve_user_id_by_usergroup($username1, "teacher"));
        $this->assertEquals(9, $user1->retrieve_user_id_by_usergroup($username2, "teacher"));
        $this->assertEquals(55, $user1->retrieve_user_id_by_usergroup($username2, "parent"));

    }

    public function testGet_class_stamp_by_id()
    {
        $usr = new user();

        $this->assertEquals(0, sizeof($usr->get_class_stamp_by_id(null)));
        $this->assertEquals("-1°noC", $usr->get_class_stamp_by_id(-1));
        $this->assertEquals("1°A", $usr->get_class_stamp_by_id(1));

        $this->assertEquals("1°C", ($usr->get_class_stamp_by_id(3)));
    }

    public function testRandom_str()
    {
        $usr = new user();
        $this->assertNotEmpty($usr->random_str(10));
        $this->assertNotEquals($usr->random_str(10), $usr->random_str(10));
    }

    public function testCheck_fiscal_code()
    {

        $user = new user();
        $this->assertEquals(1, $user->check_fiscal_code('HHYSGU96L21E241R'), 'testCheck_fiscal_code failed: valid fiscal code is to be accepted');
    }

}
