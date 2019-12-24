<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Activate new parents");
$site->setPage($page);
$officer = new officer();

if(!$officer ->is_logged() ){
	header("location: /error.php?errorID=19");
	exit();
}

$content = "";
if ($_GET["action"] != "generateTimetable") {
	$content = '
    <!-- Using container, replace with card if you want  -->
    <div class="container article-clean">
        <div class="wrapper text-center">
            <br>
            <h1>TimeTable generation</h1>
            <p class="lead">Click on the button to generate the timetables.<br></p>
            <a class="btn btn-primary" href="./generateTimetables.php?action=generateTimetable"role="button">Generate Timetables</a>
        </div>
    </div>';
} else {
    // 1. Load all the teachers into a structured array[Topic][DayOfWeek][HourSlot][teacherID]
    // 2. Load all the classes information into a structured array
    // 3. Compute the minimum requirements so we can warn the user if not enough teacher are present (in real no maximum hours is given for a teacher, skipping it for now)
    // 4. For each class, for each dayOfWeek, for each hourslot: 
        // assignTeacherToClass( teacherID, specificClassID, dayOfWeek, hourSlot)
    // 5. 


    // 1. Load all the teachers into a structured array[Topic][DayOfWeek][HourSlot][teacherID] 
    // and array[teacherID]{topicsList}
    $freeTeachersPerTopic = get_topicteacher();
    $teacherTopic = get_topics_by_teacher();
    var_dump($freeTeachersPerTopic);
    print "<br><br>";
    var_dump($teacherTopic);
    print "<br><br>";
    // 2. Load all the classes information into a structured array
    $classes = get_classes_info();
    var_dump($classes);
    // 3.
    print "<br><br>";

    // 4. For each class, for each dayOfWeek, for each hourslot: 
    foreach ($classes as $specificClassID => $classInfo) {
        //var_dump($classInfo["neededTopics"]);
        echo "<br>".$specificClassID;
        for ($i=0; $i < 5; $i++) { 
            for ($j=0; $j < 6; $j++) { 
                echo array_pop($classInfo["neededTopics"]);
            }
        }
    }
    # N topics with Hn hours per week for each topic, M hourslots
    # totNeededArr[M] = (TopicID)



}
$page->setContent($content);
$site->render();

function get_topicteacher() {
    $topicTeacher = array();
    $conn = connectMySQL();
    $stmt = $conn->prepare("SELECT * FROM TopicTeacherClass");
    if (!$stmt) return false;
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res > 0) {
        while ($row = $res->fetch_assoc()) {
            // Create array structure if a new topic is read
            if(!key_exists($row["TopicID"], $topicTeacher)){
                $topicTeacher[$row["TopicID"]] = array();
                for ($i=0; $i < 5; $i++) { 
                    $topicTeacher[$row["TopicID"]][$i] = array();
                    for ($j=0; $j < 6; $j++) { 
                        $topicTeacher[$row["TopicID"]][$i][$j] = array();
                    }
                }
            } 
            // Add the teacher
            for ($i=0; $i < 5; $i++) { 
                for ($j=0; $j < 6; $j++) { 
                    array_push($topicTeacher[$row["TopicID"]][$i][$j], $row["TeacherID"]);
                }
            }
        }
    }
    return $topicTeacher;
}

// Create an array that has TeacherID as key and a set of TopicID (each one learned by that teacher)
function get_topics_by_teacher() {
    $teacherTopic = array();
    $conn = connectMySQL();
    $stmt = $conn->prepare("SELECT * FROM TopicTeacherClass ORDER BY TopicID ASC");
    if (!$stmt) return false;
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res > 0) {
        while ($row = $res->fetch_assoc()) {
            if(!key_exists($row["TeacherID"], $teacherTopic)){
                $teacherTopic[$row["TeacherID"]] = array();
            } 
            array_push($teacherTopic[$row["TeacherID"]], $row["TopicID"]);
        }
    }
    return $teacherTopic;
}

// Create an array containing the info about the classes (Year, Section, Topic with relative number of hours)
function get_classes_info() {
    // array[SpecificClassID][section] = < section>
    //array[SpecificClassID][yearClassID] = < yearClassID>
    //array[SpecificClassID][scheduledTopics][topicID] = <hoursPerTopic>
    $classes = array();
    $conn = connectMySQL();
    $stmt = $conn->prepare("SELECT SpecificClass.ID AS specificClassID, SpecificClass.YearClassID as YearClassID, Section, UploadedPath, CoordinatorTeacherID, TopicID, Hours 
                            FROM SpecificClass 
                            JOIN YearTopicHour ON SpecificClass.YearClassID=YearTopicHour.YearClassID");
    if (!$stmt) return false;
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res > 0) {
        while ($row = $res->fetch_assoc()) {
            // If a new class is given, add the information like yearID and section
            if(!key_exists($row["specificClassID"], $classes)){
                $classes[$row["specificClassID"]] = array();
                $classes[$row["specificClassID"]]["yearClassID"] = $row["YearClassID"];
                $classes[$row["specificClassID"]]["section"] = $row["Section"];
            }
            // Save with scheduledTopics key an array indexed by TopicID and with the relative hourse per week as value 
            if( !key_exists( "scheduledTopics", $classes[$row["specificClassID"]] )){
                $classes[$row["specificClassID"]]["scheduledTopics"] = array();
                $classes[$row["specificClassID"]]["neededTopics"] = array(); // an array where the TopicID is repeated  $row["Hours"] times
            }
            $hoursPerTopicArr = array();
            $hoursPerTopicArr[$row["TopicID"]] = $row["Hours"];
            array_push($classes[$row["specificClassID"]]["scheduledTopics"], $hoursPerTopicArr);
            
            // Copose neededTopics array 
            for ($i=0; $i < $row["Hours"]; $i++) { 
                array_push($classes[$row["specificClassID"]]["neededTopics"], $row["TopicID"]);
            }
            // Every times do a shuffle (this is to avoid imolementation of some sort of algorithm used to compose the timetable)
            shuffle($classes[$row["specificClassID"]]["neededTopics"]);
        }
    }
    return $classes;
}

function connectMySQL() {
    $mysqli = new mysqli(DBAddr, DBUser, DBPassword, DBName);
    /* check connection */
    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_errno);
        exit();
    }
    return $mysqli;
}

