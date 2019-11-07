<?php

require_once("../config.php");


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
      <th scope="col">Category</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">07/11/2019</th>
      <td>7</td>
      <td>Math</td>
      <td>Written</td>
    </tr>
    <tr>
      <th scope="row">07/11/2019</th>
      <td>8</td>
      <td>English</td>
      <td>Oral</td>
    </tr>
    <tr>
      <th scope="row">07/11/2019</th>
      <td>9</td>
      <td>History</td>
      <td>Laboratory</td>
    </tr>
  </tbody>
</table>

';


$page->setContent($content);
$site->render();
?>
