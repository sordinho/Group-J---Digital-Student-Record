<?php

use PHPUnit\Framework\TestCase;
require_once 'testUtility.php';
require_once "../classes/user.class.php";
require_once "../classes/sparent.class.php";

class sparentTest extends TestCase
{

    public static function setUpBeforeClass(): void
    {
        createTestDatabase();
    }
    public static function tearDownAfterClass(): void
    {
        dropTestDatabase();
    }

    public function testGet_grades()
    {
        $parentObj = new sparent();
        $this->assertSameSize($parentObj->get_grades(array()), array(), "get_grades(null) returns an empty array");

    }

    public function testSet_current_child()
    {
        $_SESSION["parentID"] = 1;
        $parentObj = new sparent();
        $parentObj->set_current_child(2);
        $this->assertEquals(2, $_SESSION["curChild"], "sparentTest: testSet_current_child");
    }

    public function testGet_current_child()
    {
        $_SESSION["parentID"] = 1;
        $parentObj = new sparent();
        $this->assertEquals(2, $parentObj->get_current_child(0), "ok");

    }

    public function test__construct()
    {
        $_SESSION["parentID"] = 1;
        $parentObj = new sparent();
        $this->assertEquals(1, $parentObj->getParentId(), "parent object ID equals to session ID");
    }

    public function testRetrieve_and_register_childs()
    {
        $_SESSION["parentID"] = 1;
        $parentObj = new sparent();
        $parentObj->retrieve_and_register_childs();
        $childsIDs = $_SESSION["childsID"];
        $this->assertEquals(1, $childsIDs[0], "ok");
    }
}
