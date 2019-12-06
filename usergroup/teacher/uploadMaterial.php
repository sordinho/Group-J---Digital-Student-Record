<?php
require_once("../../config.php");

$teacher = new teacher();

$site = new csite();
initialize_site($site);
$page = new cpage("");
$site->setPage($page);

if (!$teacher->is_logged()) {
	header("location: /error.php?errorID=19");
	exit();
}

$content .= '
        <!-- Material form register -->
        <div class="card">
            <h5 style="background-color:rgba(108,108,108,0.9);color:white" class="card-header info-color white-text text-center py-4">
                <strong>Upload material</strong>
            </h5>

            <!--Card content-->
            <div class="card-body px-lg-5 pt-0">

                <!-- Form -->
                <form class="text-center" style="color: #757575;" action="uploadCSVParentCredentials.php" enctype="multipart/form-data" method="post">
                    <p class="card-body info-color white-text text-center py-4">File Upload</p>
                    <div class="form-row">
                        <div class="col">
                            <!-- First name -->
                            <div class="md-form">
                                <input type="file" id="file" name="file" class="form-control-file">
                                <!--<label for="CSVUpload">CSV Upload</label>-->
                            </div>
                        </div>
                <!-- Sign up button -->
                <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Upload</button>
                </form>
                <!-- Form -->
                </div> 
                </div>';

$page->setContent($content);
$site->render();
?>