<?php

require_once("../../config.php");


$site = new csite();
initialize_site($site);
$page = new cpage("");
$site->setPage($page);
$teacher = new teacher();

if (!$teacher->is_logged() ) {
	header("location: /error.php?errorID=19");
	exit();
}
if(isset($_GET['operation_result'])){
	switch ($_GET['operation_result']){
		case 1:
			$content.= <<<OUT
<div class="alert alert-success" role="alert">
  Lecture successfully modified. <a href="listLectures.php" class="alert-link">Modify another lecture</a> or <a href="index.php" class="alert-link">back to your homepage.</a>
</div>
OUT;
			break;
		case 0:
			$content.= <<<OUT
<div class="alert alert-danger" role="alert">
 Error in modifying lecture. <a href="listLectures.php" class="alert-link">Retry </a> or <a href="../officer/index.php" class="alert-link">back to your homepage.</a>
</div>
OUT;

			break;
		default:
			$content.=<<<OUT
<div class="alert alert-dark" role="alert">
  Operation not allowed.
</div>
OUT;
	}
} elseif ($_GET['action'] == "edit" && isset($_GET['lectureID'])) {// is_logged should extend the base in user
	# Show form with default value equals to that in the DB for the given ID
	$lecture_info = $teacher->get_lecture_by_id($_GET['lectureID']);
	$lecture_info["Title"] = substr($lecture_info['TopicDescription'], 0, 16);// . "...";


	$content = '
				<div class="card">
					<h2 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">
						<strong>Lecture Modification</strong>
					</h2>
					<div class="card-body  px-lg-5 pt-0 mt-md-5">
						<div class="form-group">
							<form method="POST" action="listLectures.php?action=submit">
							  <div class="form-group">
							  <label for="Title">Title</label>
							  <input type="text" class="form-control" value = "' . $lecture_info["Title"] . '" name="title" id="title" placeholder="Course Introduction">
							  </div>
							  <div class="form-group" hidden>
							  <input type="text" class="form-control" value = "' . $lecture_info["TopicRecordID"] . '" name="ID" id="ID" placeholder="Course Introduction">
							  </div>
							  <div class="form-group">
							  <label for="exampleFormControlSelect2">Subject</label>
							  <input type="text" class="form-control" value = "' . $lecture_info["TopicName"] . '" name="TopicName" id="subject" disabled>
							  </div>
							  <div class="form-group">
							  <label for="exampleFormControlTextarea1">Description</label>
							  <textarea class="form-control " id="description" name="CourseDescription" placeholder="CourseDescription" rows="3">'. $lecture_info["TopicDescription"] . '</textarea>
							  </div>
							  <div class="form-group">
							  <label for="exampleFormControlTextarea1">Date</label>
							  <input type="text" class="form-control" id="date" value = "'.$lecture_info["TimeStamp"].'" name="date" disabled>
							  </div>
							  <button type="submit" class="btn btn-primary">Confirm</button>
							  
							</form>
						</div>
					</div>
                </div>
                ';

} /*elseif ($_POST["description"]) {
	# call function to edit editLecture(lectID, title, description, topicID?)
  # And show status message  (if status==ok the following should do the job)
  
	$content = '<div class="alert alert-success" role="alert">
    You just updated a topic lecture<br>In a few seconds you will be redirected to home. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
  </div>';

	$content .= "<meta http-equiv='refresh' content='2; url=" . $_SESSION["base_url"] . "' />";
}*/elseif(!empty($_POST)&&$_GET['action']=='submit'){
	$res = $teacher->modify_lecture_topic($_POST['CourseDescription'],$_POST['ID']);
	if($res){
		header("Location: listLectures.php?operation_result=1");
		die();
	}
	header("Location: listLectures.php?operation_result=0");
	die();
}else {
// Should be moved to other page and just linked in the menu

	$content = '<div class="card">
                <h2 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">
                    <strong>List of Lectures</strong>
                </h2>
                <div class="card-body  px-lg-5 pt-0 mt-md-5">
                <form>
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">Description</th>
        <th scope="col">Subject</th>
        <th scope="col">Date</th>
      </tr>
    </thead>
    <tbody>';

	$topicRecords = $teacher->get_topics_record();
	foreach ($topicRecords as $i => $row) {
		//ttc.SpecificClassID as ClassID, tc.ID as TopicID, tc.Name as TopicName, tc.Description as TopicDescription
		$title = substr($row['TopicDescription'], 0, 30) . "..."; // tODO: remove if title added to db
		$content .= '<tr>
        <th scope="row"><a class="alert-link" href="listLectures.php?action=edit&lectureID='.$row['RecordID'].'">' . $title . '</a></th>
        <td>' . $row['TopicName'] . '</td>
        <td>' . $row['TimeStamps'] . '</td>
      </tr>';
	}

	$content .= '
    </tbody>
  </table>
  </form>
  </div>';


}

$page->setContent($content);
$site->render();
?>
