<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Student Enrollment Page");
$site->setPage($page);

$content = "

<div class=\"card\">
    <h5 class=\"card-header info-color white-text text-center py-4\">
        <strong>Enter Student Master Data</strong>
    </h5>
    
    <div class=\"card-body px-lg-5 pt-0\">
        <p class=\"card-body info-color white-text text-center py-4\">Student</p>
        <form>
          <div class=\"form-row\">
            <div class=\"form-group col-md-6\">
              <label for=\"inputName\">First Name</label>
              <input type=\"text\" id=\"materialRegisterFormFirstName\" name=\"parent_first_name\" class=\"form-control\" placeholder=\"Insert Name\">
            </div>
            <div class=\"form-group col-md-6\">
              <label for=\"inputSurname\">Last Name</label>
              <input type=\"text\" id=\"materialRegisterFormLastName\" name=\"student_last_name\" class=\"form-control\"  placeholder=\"Insert Last Name\">
            </div>
          </div>
          <div class=\"form-group\">
            <label for=\"inputFC\">Fiscal Code</label>
            <input type=\"text\" class=\"form-control\" id=\"inputFC\" placeholder=\"Insert Fiscal Code\">
            <small id=\"passwordHelpBlock\" class=\"form-text text-muted\">
              Must be 16 digits.
            </small>
          </div>
          
          <button type=\"submit\" class=\"btn btn-primary\">Confirm</button>
        </form>
    </div>
</div>";

$page->setContent($content);
$site->render();