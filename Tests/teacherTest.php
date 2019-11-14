<?php


use PHPUnit\Framework\TestCase;
require_once 'testUtility.php';
require_once '../classes/user.class.php';
require_once '../classes/teacher.class.php';

class teacherTest extends TestCase
{

    public static function setUpBeforeClass():void{
        createTestDatabase();
    }
    public static function tearDownAfterClass(): void
    {
        dropTestDatabase();
    }

    public function testModify_lecture_topic()
    {
        //TODO

        $this->assertEquals("a","a");

    }

    public function testInsert_new_lecture_topic()
    {
        //TODO
        $this->assertEquals("a","a");

    }
}
