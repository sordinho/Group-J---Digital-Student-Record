<?php

require_once("../../config.php");

$parentObj=new sparent();

$site = new csite();
initialize_site($site);
$page = new cpage("Parent");
$site->setPage($page);
$sparent = new sparent();

if($_GET['action'] == "switchChild" && $sparent->is_logged()){// is_logged should extend the base in user
  $new_childID = intval($_GET["childID"]);
  $sparent->set_current_child($new_childID);
  $content = '<div class="alert alert-success" role="alert">
    You just switched child<br>In a few seconds you will be redirected to home. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
  </div>';
  $content .= "<meta http-equiv='refresh' content='2; url=" . $_SESSION["base_url"]. "' />";

}
else{
// Should be moved to other page and just linked in the menu

  $content='
  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">Date</th>
        <th scope="col">Grade</th>
        <th scope="col">Subject</th>
        <th scope="col">Professor</th>
        <th scope="col">Category</th>
      </tr>
    </thead>
    <tbody>';

  //TODO get the current child selected by the parent
  $grades = $parentObj -> get_grades($sparent->get_current_child());

  while($row = $grades->fetch_row()){
        //t.Name, Mark, Timestamp, u.Surname
      $content.='<tr>
        <th scope="row">'.$row[2].'</th>
        <td>'.$row[1].'</td>
        <td>'.$row[0].'</td>
        <td>'.$row[3].'</td>
        <td>TODO</td>
      </tr>';
  }

  $content.='
    </tbody>
  </table>';

}

$page->setContent($content);
$site->render();
?>
