<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Administrative Officier homepage");
$site->setPage($page);

/*here content*/

//1 get generated credentials
// $officier = new AdministrativeOfficier();
// $res = $officier->getGeneratedCredentials();
//2 show generated credentials in a tabular fashion
// while($row = $res->fetch_row()){
//      print a table
// }
//3 confirm the operation
// ask for user confirmation
//3.a send mail
// IF user confirms --> notify mailer class

$page->setContent($content);
$site->render();
