<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Administrative Officer");
$site->setPage($page);

$officer = new officer($_SESSION);

if(!$officer ->is_logged() || $officer ->get_officer_ID()==-1){
	$content = '
    <div class="alert alert-warning" role="warning">
        You are not authorized. If you are in a hurry <a href="./index.php" class="alert-link">just click here!</a>
    </div> ';
	$content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
	$page->setContent($content);
	$site->render();
	exit();
}
$content = <<<OUT
<div class="card">
    <h5 class="card-header info-color white-text text-center py-4">
        <strong>Class selection for enrollment </strong>
    </h5>

    <!--Card content-->
    <div class="card-body px-lg-5 pt-0">
        <div class="container">
            <div class="row mx-md-n5">
                <div class="col-md px-md-5">
                    <h5 class="card-body info-color white-text text-center py-4">
                        <strong>Select a class</strong>
                    </h5>
                </div>
                <div class="col-md px-md-5">
                    <div class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Class
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">1 A</a>
                                <a class="dropdown-item" href="#">2 A</a>
                                <a class="dropdown-item" href="#">3 A</a>
                                <a class="dropdown-item" href="#">4 A</a>
                                <a class="dropdown-item" href="#">5 A</a>
                                <a class="dropdown-item" href="#">Patate</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
OUT;


$page->setContent($content);
$site->render();
