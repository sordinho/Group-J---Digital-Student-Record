<?php

require_once 'testUtility.php';

use PHPUnit\Framework\TestCase;
require_once "../classes/user.class.php";

class userTest extends TestCase
{

    public function testGet_name()
    {
        $_SESSION['name'] = "test";
        $this->assertEquals("test",get_name(),"userTest : test_get_name wrong returned value");
    }

    public function test__construct()
    {

    }

    public function testIs_secure_password()
    {
        //case password < 5
        $user = new user();
        $this->assertFalse($user->is_secure_password('cat'), 'is_secure_password accepts lenght(password) < 5');
        //case password == 5
        $this->assertTrue($user->is_secure_password('cover'), 'is_secure password not accepting lenght(password) == 5');
        //case password > 5
        $this->assertTrue($user->is_secure_password('elephant'), 'is_secure_password not accepting lenght(password) > 5');
    }

    public function testIs_email()
    {
        $user = new user();
        $this->assertEquals($user->is_email('adamo@paradiso.it'),1,'testIs_email failed: valid email is to be accepted');
        $this->assertEquals($user->is_email('adamoparadiso.it'),0,'testIs_email failed: non-valid email is not to be accepted');
        $this->assertEquals($user->is_email('adamo@paradisoit'),0,'testIs_email failed: non-valid email is not to be accepted');
        $this->assertEquals($user->is_email('adamoparadisoit'),0,'testIs_email failed: non-valid email is not to be accepted');
        $this->assertEquals($user->is_email('@paradiso.it'),0,'testIs_email failed: non-valid email is not to be accepted');
        $this->assertEquals($user->is_email('@paradiso'),0,'testIs_email failed: non-valid email is not to be accepted');
        $this->assertEquals($user->is_email('adamo@'),0,'testIs_email failed: non-valid email is not to be accepted');
    }

    public function testIs_logged()
    {

    }

    public function testGet_id()
    {

    }

    public function testGet_base_url()
    {

    }

    public function testGet_link_logout()
    {

    }

    public function testGet_error()
    {

    }

    public function testGet_usergroup()
    {

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
