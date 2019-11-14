<?php


use PHPUnit\Framework\TestCase;
require_once 'testUtility2.php';
require_once '../classes/user.class.php';
require_once '../classes/teacher.class.php';

class teacherTest extends TestCase {


    public function testModify_lecture_topic() {
        $_SESSION["teacherID"] = 1;
        $topicID = 1;
        $_SESSION["id"]=3;
        $description = "Test topic description";
        $modifiedDescription = "MODIFIED Test topic description";
        $teacherObject = new Teacher($_SESSION);
        $teacherID = $teacherObject->get_teacher_ID();
        $timestamp = date("Y-m-d H:i:s");

        // Insert a topic record to modify
        $this->assertNotNull($teacherObject->insert_new_lecture_topic($description, $topicID, $timestamp));

        // Try to modify the topic record
        $teacherObject->modify_lecture_topic($modifiedDescription,);

    }

    public function testInsert_new_lecture_topic()
    {
        //TODO
        // variables
        $_SESSION["teacherID"]=1;
        $teacherObject = new Teacher($_SESSION);
        $topicID=1;
        $description="Lecture 1 topic 1";
        $dateActualDate=date("Y-m-d H:i:s");

        //perform insertion in the DB
        $this->assertNotNull($teacherObject->insert_new_lecture_topic($description,$topicID,$dateActualDate));

        $topicRecordID = perform_SELECT_return_single_value("SELECT ID FROM TopicRecord WHERE Timestamp ='$dateActualDate'");
        //printf("topicRecordID: %d\n",$topicRecordID);

        $count = perform_SELECT_return_single_value("SELECT COUNT(*) FROM TopicRecord WHERE ID =$topicRecordID");
        //printf("Count: %d\n",$count);

        $this->assertEquals($count,1,"Test non superato!");

        $this->assertTrue(perform_INSERT_or_DELETE("DELETE FROM TopicRecord WHERE ID=$topicRecordID"));

        //Prints for debug
        //printf("TeacherID: %d\nDateActual: %s\nDescription: %s\nTopicID: %d\nSpecifiClassID: %d",$teacherID,$dateActualDate,$description,$topicID,$specificClassID);

    }
}
