<?php

require_once("../../config.php");


$site = new csite();
initialize_site($site);
$page = new cpage("Parent");
$site->setPage($page);
$teacher= new teacher();

if(!$teacher ->is_logged()){
	$content = '
    <div class="alert alert-warning" role="warning">
        You are not authorized. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
    </div> ';
	$content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
	$page->setContent($content);
	$site->render();
  render_page($content, '');
}

if($_GET['action'] == "edit" && isset($_GET['lectureID'])){// is_logged should extend the base in user
  # Show form with default value equals to that in the DB for the given ID
  

}
elseif ($_POST["description"]) {
  # call function to edit editLecture(lectID, title, description, topicID?)
  # And show status message  (if status==ok the following should do the job)
  $content = '<div class="alert alert-success" role="alert">
    You just updated a topic lecture<br>In a few seconds you will be redirected to home. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
  </div>';
  $content .= "<meta http-equiv='refresh' content='2; url=" . $_SESSION["base_url"]. "' />";
}
else{
// Should be moved to other page and just linked in the menu

  $content='
  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">Title</th>
        <th scope="col">Subject</th>
        <th scope="col">Date</th>
      </tr>
    </thead>
    <tbody>';

  //TODO get the current child selected by the parent
  $grades = $parentObj -> get_grades($sparent->get_current_child());

  while($row = $grades->fetch_row()){
        //t.Name, Mark, Timestamp, u.Surname
      $content.='<tr>
        <th scope="row">'.$row[0].'</th>
        <td>'.$row[1].'</td>
        <td>'.$row[2].'</td>
      </tr>';
  }

  $content.='
    </tbody>
  </table>';

}

$page->setContent($content);
$site->render();
?>
