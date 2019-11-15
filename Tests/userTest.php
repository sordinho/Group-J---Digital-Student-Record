<?php

require_once 'testUtility.php';

use PHPUnit\Framework\TestCase;
require_once "../classes/user.class.php";

class userTest extends TestCase
{

    private $user;

    protected function setUp(): void {
        $user = new user();
    }

    public function testGet_name()
    {
        $_SESSION['name'] = "test";
        $user = new user();
        $this->assertEquals("test",$user->get_name(),"userTest : test_get_name wrong returned value");
    }

    public function test__construct()
    {
        //todo
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
        $this->assertEquals(1,$user->is_email('adamo@paradiso.it'),'testIs_email failed: valid email is to be accepted');
        $this->assertEquals(0,$user->is_email('adamoparadiso.it'),'testIs_email failed: non-valid email is not to be accepted');
        $this->assertEquals(0,$user->is_email('adamo@paradisoit'),'testIs_email failed: non-valid email is not to be accepted');
        $this->assertEquals(0,$user->is_email('adamoparadisoit'),'testIs_email failed: non-valid email is not to be accepted');
        $this->assertEquals(0,$user->is_email('@paradiso.it'),'testIs_email failed: non-valid email is not to be accepted');
        $this->assertEquals(0,$user->is_email('@paradiso'),'testIs_email failed: non-valid email is not to be accepted');
        $this->assertEquals(0,$user->is_email('adamo@'),'testIs_email failed: non-valid email is not to be accepted');
    }

    public function testIs_logged()
    {
        $_SESSION['id']="test";
        $user = new user();
        $this->assertTrue($user->is_logged(),"userTest: testIs_logged function is_logged should return true.");
        unset($_SESSION['id']);
        $this->assertFalse($user->is_logged(),"userTest: testIs_logged function is_logged should return false.");
    }

    public function testGet_id()
    {
        $_SESSION['id'] = "1";
        $user = new user();
        $this->assertEquals("1",$user->get_id(),"userTest : testGet_id wrong returned value");
        unset($_SESSION['id']);
        $this->assertEquals('',$user->get_id(),"userTest : testGet_id wrong returned value");
    }

    public function testGet_base_url()
    {
        $_SESSION['base_url'] = "/test/";
        $user = new user();
        $this->assertEquals("/test/",$user->get_base_url(),"userTest : testGet_base_url wrong returned value");
        unset($_SESSION['base_url']);
        $this->assertEquals('',$user->get_base_url(),"userTest : testGet_base_url wrong returned value");
    }

    public function testGet_link_logout()
    {
        $_SESSION['id'] = '1';
        $user = new user();
        $this->assertEquals( '<a href="' . $this->Urls['logout_page'] . '" class="logout">Logout</a>',$user->get_link_logout(), "userTest : testGet_link_logout wrong returned value");
        unset($_SESSION['id']);
        $this->assertEquals( '',$user->get_link_logout(), "userTest : testGet_link_logout wrong returned value");
    }

    public function testGet_error()
    {
        //TODO
    }

    public function testGet_usergroup()
    {
        $_SESSION['usergroup'] = 'test';
        $user = new user();
        $this->assertEquals( 'test',$user->get_usergroup(), "userTest : get_usergroup wrong returned value");
        unset($_SESSION['usergroup']);
        $this->assertEquals( '',$user->get_usergroup(), "userTest : get_usergroup wrong returned value");
    }

    public function testUser_login()
    {

    }

    public function testGet_surname()
    {

    }

    public function testRegister()
    {

    }

    public function testGet_username()
    {

    }

    public function testGetUserData()
    {

    }
}
