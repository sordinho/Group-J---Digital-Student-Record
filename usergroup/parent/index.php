<?php

require_once("../../config.php");

$parentObj=new sparent();

$site = new csite();
initialize_site($site);
$page = new cpage("Parent");
$site->setPage($page);


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

$grades = $parentObj -> get_grades(3);

while($row = $grades->fetch_row()){
      //Mark, Timestamp, u.Surname
    $content.='<tr>
      <th scope="row">'.$row[1].'</th>
      <td>'.$row[0].'</td>
      <td>TODO</td>
      <td>'.$row[3].'</td>
      <td>TODO</td>
    </tr>';
}

$content.='
  </tbody>
</table>';


$page->setContent($content);
$site->render();
?>
