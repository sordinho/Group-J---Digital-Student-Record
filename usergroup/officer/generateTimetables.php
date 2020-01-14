<?php
require_once("../../config.php");
//TODOs:
// A timeTable class would be better suited to contain the logic of this page
$site = new csite();
initialize_site($site);
$page = new cpage("Generate timetables");
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
            <a class="btn btn-warning" href="./generateTimetables.php?action=generateTimetable"role="button">Delete and generate new Timetables</a>
        </div>
    </div>';
} else {
    //0. DElete already defined timetables
    // 1. Load all the teachers into a structured array[Topic][DayOfWeek][HourSlot][teacherID]
    // 2. Load all the classes information into a structured array
    // 3. Compute the minimum requirements so we can warn the user if not enough teacher are present (in real no maximum hours is given for a teacher, skipping it for now)
    // 4. For each class, for each dayOfWeek, for each hourslot: 
        // assignTeacherToClass( teacherID, specificClassID, dayOfWeek, hourSlot)
    // 5. 
    $failflag = true;
    for ($max_try=0; $max_try < 40 && $failflag; $max_try++) { 
        deleteTimetables();
        // 1. Load all the teachers into a structured array[Topic][DayOfWeek][HourSlot][teacherID] 
        // and array[teacherID]{topicsList}
        $timetables = array();
        $teacher_assigned_classes = array();
        $freeTeacherByTopicDayHourslot = get_topicteacher(); // Contains an array indexed by topicID and containing a list of teacherID teaching that subject
        $topicsByTeacher = get_topics_by_teacher(); // Array indexed by teacherID cointaining all the topicID learnt by that teacher
        $teachersByTopic = get_teachers_by_topic(); // 
        //var_dump($freeTeacherByTopicDayHourslot);
        // 2. Load all the classes information into a structured array
        $classes = get_classes_info();
        // 3. Skip for now

        // 4. For each class, for each dayOfWeek, for each hourslot: 
        // pick a teacher and assign it to a specific class for a specific subject
        $class_topic_teacher_assignment = array();

        $failflag = false;
        foreach ($classes as $specificClassID => &$classInfo) {
            //var_dump($classInfo["neededTopics"]);
            if($failflag){
            break;
            }

            #echo "<br>".$specificClassID."<br>";
            #echo "Tothours: ".sizeof($classInfo["neededTopics"]);
            $maxHoursReached = false;

            for ($i=0; $i < 5 && !$maxHoursReached && !$failflag; $i++) { 
                for ($j=0; $j < 6 && !$maxHoursReached && !$failflag; $j++) { 
                    #print $i.$j."<BR>";
                    if(sizeof($classInfo["neededTopics"]) == 0){
                        $maxHoursReached = true;
                        break;
                    }
                    $success = false;
                    //$i = sizeof($teachersByTopic[$needed_topic]);// try to reassign, skip for now for semplicity pourpose
                    $max_trials = 40;
                    while(!$success && $max_trials > 0){
                        $needed_topic = array_pop($classInfo["neededTopics"]);
                        #print("<br>Counter: $i, $j <br>");
                        // Read a needed topic
                        // a) check if we hadn't already assigned a teacher to a class, given a specific subject, let's assign one
                        if(!key_exists($specificClassID, $class_topic_teacher_assignment) || !key_exists($topicID, $class_topic_teacher_assignment[$specificClassID]) ){
                            // search for a free teacher and assign it to a class
                            assign_teacher_to_class($teacher_assigned_classes, $teachersByTopic, $topicsByTeacher, $class_topic_teacher_assignment, $specificClassID, $needed_topic);
                            //var_dump($teacher_assigned_classes);
                            //var_dump($class_topic_teacher_assignment);
                        }
                        // b) Add entry to timetable given topic, teacher, class, day and hour
                        //add_timetable_entry($timetables, $freeTeacherByTopicDayHourslot, $class_topic_teacher_assignment, $specificClassID, $needed_topic, $i, $j);
                        // What if the teacher is already busy at that hour? Should I assign another teacher to that class? Should I do a switch in the timetable regarding the topic?
                        $max_trials--;
                        $success = add_timetable_entry($timetables, $freeTeacherByTopicDayHourslot, $class_topic_teacher_assignment, $specificClassID, $needed_topic, $i, $j);
                        if (!$success){
                            // retry with a different topic 
                            array_push($classInfo["neededTopics"], $needed_topic);
                            shuffle($classInfo["neededTopics"]);
                        }
                        //echo "sof: ".sizeof($classInfo["neededTopics"])."<br>";
                    }

                    if(!$success){
                        $failflag = true;
                        $max_try++;
                        ##die("Please try again, a rare issue occurred.");
                        // should be replaced by a reassign teacher system or by iterating the whole code until no issue
                        // btw it should be ok at this time
                    }

                }
            }
        }
        # N topics with Hn hours per week for each topic, M hourslots
        # totNeededArr[M] = (TopicID)
    }
    if(!$failflag){
        foreach ($timetables as $classID => $data) {
            //print $classID."<br> ";
            if (!$officer->check_weekly_hours($data, $classID)) {
                die("Error somewhere".$classID);
            }
            $officer->set_timetable_class($data, $classID);
        }
        var_dump($timetables);
    }
    else{
        die("An error occured. Are you sure you have enough teacher to fullfill the requirements?");
    }
}
$page->setContent($content);
$site->render();

function add_timetable_entry(&$timetables, &$freeTeacherByTopicDayHourslot, $class_topic_teacher_assignment, $specificClassID, $topicID, $i, $j){
    if(!key_exists($specificClassID, $timetables)){
        $timetables[$specificClassID] = array();
    }
    $teacherID = $class_topic_teacher_assignment[$specificClassID][$topicID];
    // check if the teacher isn't already in another classrom at the same time/day
    if(in_array($teacherID, $freeTeacherByTopicDayHourslot[$topicID][$i][$j]) ){
        $timetables[$specificClassID][$i][$j] = $topicID."|".$teacherID."|"."insert";

        $delkey = array_search($teacherID, $freeTeacherByTopicDayHourslot[$topicID][$i][$j]);
        unset($freeTeacherByTopicDayHourslot[$topicID][$i][$j][$delkey]);
        #print("<br>".$specificClassID."[$i][$j] -> $teacherID ($topicID)");
        // TODO: remove availability also from the other topics teached by that teacher foreach blabla
        return true;
    }
    else{
        #print("<br>Failure:".$specificClassID."[$i][$j] -> $teacherID ($topicID)");
        #print("Retry, improve this");
        return false;
    }
}

# TODO: should I also check here if we have timetables not compatible between classes assigned to a teacher?
function assign_teacher_to_class(&$teacher_assigned_classes, $teachersByTopic, $topicsByTeacher, &$class_topic_teacher_assignment, $specificClassID, $topicID){
    if(key_exists($topicID, $class_topic_teacher_assignment[$specificClassID])){
        return false;
        // this class has already a teacher assigned
    }
    //$class_topic_teacher_assignment[$specificClassID] = array();

    // Find a free teacher, following condition must be satisfied:
    // - Number of classes assigned to the teacher < 3 (let's assume 1 teacher can't have more, an improvement could be adding to db the number of class or hours for the teacher)
    $assigned_teacherID = -1;
    // Iterate over the teacher of a given subject
    //var_dump($teacher_assigned_classes); // DEBUG
    //var_dump($teachersByTopic);// DEBUG
    //print("<br>TopicID: $topicID <br>");// DEBUG
    $candidateteacher_classes = 3;
    foreach ($teachersByTopic[$topicID] as $teacherID) {
        //print("Teacher candidate: $teacherID");// DEBUG
        // if a teacher wasn't assigned to a class just do it without iterating over the whole array
        if(!key_exists($teacherID, $teacher_assigned_classes)){
            $assigned_teacherID = $teacherID;
            break;
        }
        //Try to find  the most available teacher if more then one are available
        if( sizeof($teacher_assigned_classes[$teacherID]) < $candidateteacher_classes && sizeof($teacher_assigned_classes[$teacherID]) < 3 ){
            $assigned_teacherID = $teacherID;
            $candidateteacher_classes = sizeof($teacher_assigned_classes[$teacherID]);
        }
    }
    if($assigned_teacherID == -1)
        die("Not enough teacher?");
    #print("Choosen teacher: $assigned_teacherID");

    // Add the teacher to the class topic structure
    $class_topic_teacher_assignment[$specificClassID][$topicID] = $assigned_teacherID;
    // and add the class to the teacher assigned class array
    $teacher_assigned_classes[$assigned_teacherID][] = $specificClassID;
}

function assign_hourslot_to_teacher($teacher_assigned_classes, $freeTeacherByTopicDayHourslot, $topicsByTeacher, $class_topic_teacher_assignment, $specificClassID, $topicID){
    if(key_exists($topicID, $class_topic_teacher_assignment[$specificClassID])){
        return false;
        // this class has already a teacher assigned
    }
}

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
    $topicsByTeacher = array();
    $conn = connectMySQL();
    $stmt = $conn->prepare("SELECT * FROM TopicTeacherClass ORDER BY TopicID ASC");
    if (!$stmt) return false;
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res > 0) {
        while ($row = $res->fetch_assoc()) {
            if(!key_exists($row["TeacherID"], $topicsByTeacher)){
                $topicsByTeacher[$row["TeacherID"]] = array();
            } 
            array_push($topicsByTeacher[$row["TeacherID"]], $row["TopicID"]);
        }
    }
    return $topicsByTeacher;
}

// Create an array that has TopicID as key and a set of TeacherID  as values
function get_teachers_by_topic() {
    $teachersByTopic = array();
    $conn = connectMySQL();
    $stmt = $conn->prepare("SELECT * FROM TopicTeacherClass ORDER BY TopicID ASC");
    if (!$stmt) return false;
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res > 0) {
        while ($row = $res->fetch_assoc()) {
            if(!key_exists($row["TopicID"], $teachersByTopic)){
                $teachersByTopic[$row["TopicID"]] = array();
            }
            array_push($teachersByTopic[$row["TopicID"]], $row["TeacherID"]);
        }
    }
    return $teachersByTopic;
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
function deleteTimetables(){
    $conn = connectMySQL();
    $classID = intval($classID);
    $stmt = $conn->query("DELETE FROM Timetables WHERE 1");
}
