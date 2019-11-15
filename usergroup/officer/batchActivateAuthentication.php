<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Register new parent");
$site->setPage($page);
$officier = new officer();
if(!$officier->get_officer_ID()){
    $content = '
    <div class="alert alert-warning" role="warning">
        You are not authorized. If you are in a hurry <a href="index.php" class="alert-link">just click here!</a>
    </div> ';
    $content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
    $page->setContent($content);
    $site->render();
    render_page($content, '');
}// should call get_error TODOs:
$num = 1;
$content="";

if($_GET["action"] != "activate"){
    $content = '
    <!-- The container  -->
    <div class="container article-clean">
        <div class="wrapper">
            <br/>
            <h1>Batch parent activation process</h1>
            <p class="lead">Click on the button to generate the parent authentication data and send them by mail.<br></p>
            <a class="btn btn-primary" href="./batchActivateAuthentication.php?action=activate"role="button">Parent activation</a>
        </div>
    </div>';
}else{
    // call method to get the current inactive account
    $parents = $officier->get_parents_without_access_credentials();
    if(count($parents) == 0){
        //todo edit this
        $content=<<<OUT
<div class="alert alert-danger" role="alert">
 There are no parents without credentials.<a href="index.php" class="alert-link">Back to your homepage.</a>
</div>
OUT;
    } else {
        foreach($parents as $parent){
            $pwd = $officier->generate_and_register_password($parent['ID']);
            if($pwd == ""){
                //todo
                $content=<<<OUT
<div class="alert alert-danger" role="alert">
 Error in sending parent's credentials. <a href="batchActivateAuthentication.php" class="alert-link">Retry </a> or <a href="index.php" class="alert-link">back to your homepage.</a>
</div>
OUT;
                die(); //todo edit this
            } else {
                $msg = "Dear parent,
                        Your access credentials to the digital student record system are : 
                        - username : ".$parent['Email']."
                        - password : ".$pwd." 
                        Best Regards
                        The school administration.";
                //todo edit this
                mail($parent['Email'],"Access Credentials",$msg);
            }
        }
        $content.=<<<OUT
<div class="alert alert-success" role="alert">
  Parent credentials successfully generated and sent. <a href="index.php" class="alert-link">Back to your homepage.</a>
</div>
OUT;
    }
}
$page->setContent($content);
$site->render();
