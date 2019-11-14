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
        $teacherObject = new Teacher();

        $this->assertEquals("a","a");

    }

    public function testInsert_new_lecture_topic()
    {
        //TODO
        $_SESSION["teacherID"]=1;
        $teacherObject = new Teacher($_SESSION);
        $topicID=1;
        $dateActual="2019-11-14 14:10:10";
        $specificClassID=1;
        $description="Lecture 1 topic 1";
        $teacherID=$teacherObject->get_teacher_ID();
        //TODO
        //printf("%d",$teacherObject->get_teacher_ID());

        //perform insertion in the DB
        perform_INSERT_or_DELETE("INSERT INTO topicrecord(TeacherID, Timestamp, Description, TopicID, SpecifiClassID) VALUES($teacherID,$dateActual,$description,$topicID,$specificClassID");

        //Verify that the insertion has been executed correctly
        $count = perform_SELECT_return_single_value(
            "SELECT COUNT(*) FROM topicrecord WHERE TeacherID=$teacherID AND Timestamp='$dateActual' AND Description='$description' AND TopicID=$topicID AND SpecifiClassID=$specificClassID"
        );

//        //add_top("Packages");
//        $max_after = perform_SELECT_return_single_value(
//            "SELECT MAX(TicketNumber) FROM Queue WHERE ServiceID =1;"
//        );
//         printf("\n\n%d---->%d\n\n",$max_before,$max_after);
        //$this->assertTrue($max_after == ($max_before + 1), "TestQueue: test_add_top not performed correctly or not performed");*/



        $this->assertEquals("a","a");

    }
}
