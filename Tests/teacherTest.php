<?php


use PHPUnit\Framework\TestCase;
require_once 'testUtility.php';
require_once '../classes/user.class.php';
require_once '../classes/teacher.class.php';

class teacherTest extends TestCase {

    public static function setUpBeforeClass(): void {
        createTestDatabase();
    }

    public static function tearDownAfterClass(): void {
        dropTestDatabase();
    }


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
        $dateActualString=strtotime($dateActualDate);
        $specificClassID=1;
        $teacherID=$teacherObject->get_teacher_ID();
        $topicRecordID=-1;

        //TODO
        //printf("%d",$teacherObject->get_teacher_ID());

        //perform insertion in the DB
        $this->assertNotNull($teacherObject->insert_new_lecture_topic($description,$topicID,$dateActualDate));

        $conn=connectMySQLTests();

        $stmt = $conn->prepare("SELECT ID FROM TopicRecord WHERE Timestamp = ?;");
        $stmt->bind_param('s', $dateActualDate);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows <= 0) {
            return false;
        }else{
            $row=$res->fetch_row();
            printf("topicRecordID: %d\n",$row[0]);
            $topicRecordID=$row[0];
        }
        $res->close();


        $stmt = $conn->prepare("SELECT COUNT(*) FROM TopicRecord WHERE ID = ?;");
        $stmt->bind_param('i', $topicRecordID);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows <= 0) {
            return false;
        }else{
            $row=$res->fetch_row();
            printf("Count: %d\n",$row[0]);
            $this->assertEquals($row[0],1,"Test superato con successo!");
        }
        $res->close();
        $conn->close();

        //Prints for debug
        printf("TeacherID: %d\nDateActual: %s\nDescription: %s\nTopicID: %d\nSpecifiClassID: %d",$teacherID,$dateActualDate,$description,$topicID,$specificClassID);

    }
}
