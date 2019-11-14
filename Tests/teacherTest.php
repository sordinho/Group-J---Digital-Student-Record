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
//        $login["unsername"]="TeacherEmail1";
//        $login["password"]="frontoffice1";
//
//        $login=array("TeacherEmail1","frontoffice1");
//        $user=new user($login);
//        $user->user_login($login);
<<<<<<< HEAD
        $_SESSION["teacherID"]=1;
        $teacherObject = new Teacher($_SESSION);
        $topicID=1;
        $description="Lecture 1 topic 1";
        $dateActualDate=date("Y-m-d H:i:s");
        $dateActualString=strtotime($dateActualDate);
        $specificClassID=1;
        $teacherID=$teacherObject->get_teacher_ID();
        $topicRecordID=-1;
=======
		$_SESSION["teacherID"]=1;
		$teacherObject = new Teacher($_SESSION);
		$topicID=1;
		$description="Lecture 1 topic 1";
		$dateActualDate=date("Y-m-d H:i:s");
		$dateActualString=strtotime($dateActualDate);
		$specificClassID=1;
		$teacherID=$teacherObject->get_teacher_ID();

>>>>>>> master

		//TODO
		//printf("%d",$teacherObject->get_teacher_ID());

		//perform insertion in the DB
		$this->assertNotNull($teacherObject->insert_new_lecture_topic($description,$topicID,$dateActualDate));

<<<<<<< HEAD



        $conn=connectMySQLTests();

        $stmt = $conn->prepare("SELECT ID FROM TopicRecord WHERE Timestamp = ?;");
        $stmt->bind_param('s', $dateActualDate);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows <= 0) {
            return false;
        }else{
            $row=$res->fetch_row();
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
            //printf("Count: %d\n",$row[0]);
            $this->assertEquals($row[0],1,"Test superato con successo!");
        }
        $res->close();
        $conn->close();

        //Prints for debug
        printf("TeacherID: %d\nDateActual: %s\nDescription: %s\nTopicID: %d\nSpecifiClassID: %d",$teacherID,$dateActualDate,$description,$topicID,$specificClassID);
=======
		//Prints for debug
		printf("TeacherID: %d\nDateActual: %s\nDescription: %s\nTopicID: %d\nSpecifiClassID: %d",$teacherID,$dateActualDate,$description,$topicID,$specificClassID);
>>>>>>> master


//        $conn = $teacherObject->connectMySQL();
//
//        if ($result = $conn->query($sql)) {
//            $row = $result->fetch_array();
//            $value = $row[0];
//
//            $result->close();
//            return  $value;
//        } else {
//            printf("Error message: %s\n", $conn->error);
//        }

		//Verify that the insertion has been executed correctly
		$count = perform_SELECT_return_single_value(
			"SELECT COUNT(*) FROM topicrecord WHERE TeacherID=$teacherID AND Timestamp=$dateActualString AND Description='$description' AND TopicID=$topicID AND SpecificClassID=$specificClassID"
		);

		printf("\n%d",$count);


		$this->assertEquals("a", "a");

	}
}
