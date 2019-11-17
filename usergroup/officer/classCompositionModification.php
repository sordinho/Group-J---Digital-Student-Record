<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Class Composition Modification Page");
$site->setPage($page);



$content = "<div class=\"card\">
                <div class=\"card-body \">
                    <form>
                      <div class=\"input-group \">
                          <div class=\"input-group-prepend\">
                            <label class=\"input-group-text\" for=\"inputGroupSelect01\">Select The Class</label>
                          </div>
                          <select class=\"custom-select\" id=\"inputGroupSelect01\">
                            <option selected>Choose The Class</option>";

$officer = new officer();
if(!$officer->get_officer_ID()){
    $content = '
    <div class="alert alert-warning" role="warning">
        You are not authorized. If you are in a hurry <a href="index.php" class="alert-link">just click here!</a>
    </div> ';
    $content .= "<meta http-equiv='refresh' content='2; url=" . PLATFORM_PATH . "' />";
    $page->setContent($content);
    $site->render();
    render_page($content, '');
}// should call get_error TODOs:

$classes = $officer->get_Class_List();

if(count($classes) == 0){
        $content=<<<OUT
                        <div class="alert alert-danger text-center" role="alert">
                        NO CLASSES AVAILABLE
                        </div>
                    OUT;
    } else {
        foreach ($classes as $class) {
            $content .= "<option value=\"1\">" . $class['YearClassID'] . " " . $class['Section'] . "</option>";
        }
    }

$content.="
                              </select>
                          </div>
                        </form>
                    </div>
                </div>";

$page->setContent($content);
$site->render();