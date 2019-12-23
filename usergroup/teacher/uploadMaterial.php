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


if(!isset($_POST) || !isset($_FILES["file"])){

    // Get classes
    $classes = $teacher->get_assigned_classes_names();
    foreach ($classes as $class) {
        $class_str = $class['YearClass'] . $class['Section'];
        $class_list .= "<option value='{$class['ClassID']}'>" . $class_str . "</option>";
    }
    $topics = $teacher->get_topics();
    foreach ($topics as $topic) {
        $topic_list .= "<option value='{$topic['TopicID']}'>" . $topic['TopicName'] . "</option>";
    }

    $content = '
            <!-- Material form register -->
            <div class="card">
                <h5 style="background-color:rgba(108,108,108,0.9);color:white" class="card-header info-color white-text text-center py-4">
                    <strong>Upload material</strong>
                </h5>

                <!--Card content-->
                <div class="card-body px-lg-5 pt-0 mt-md-5">

                    <!-- Form -->
                    <form  style="color: #757575;" action="" enctype="multipart/form-data" method="post">
                        <p class="info-color white-text text-center py-4">File Upload</p>
                            <div class="form-group">
                                <input type="file" id="file" name="file" class="form-control-file">
                                <!--<label for="FileUpload">File upload</label>-->
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlSelect2">Subject select</label>
                                <select class="custom-select" name="topicID" id="topicID">
                                    ' . $topic_list . '
                                </select>
                            </div>
                            <!-- Class selection -->
                            <div class="form-group">
                                <label for="exampleFormControlSelect3">Class select</label>
                                <select class="custom-select" name="classID" id="classID">'
                                . $class_list
                            .'  </select>
                            </div>

                            <div class="form-group">
                                <label for="description">Description of the file</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            
                    <!-- Sign up button -->
                    <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Upload</button>
                    </form>
                    <!-- Form -->
                    </div> 
                    </div>';
}
else{
    # more security docs:https://stackoverflow.com/questions/10464948/getimagesize-not-returning-false-when-it-should/10464964#10464964
    if(!isset($_POST["classID"]) || !isset($_POST["topicID"])){
        header("location: /error.php?errorID=21");
        exit();
    }
    
    # Check if fields are set TODO:
    if ($_FILES["file"]["error"] > 0 ||  $_FILES["file"]["type"] == 'text/php') {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
        exit();
    }

    //Given a string containing the path of a file or directory, this function will return the parent directory's path that is *levels* (2) up from the current directory
    $uptwo = dirname(__DIR__, 2);
    $uploaddir = $uptwo.'/uploads/';
    //Given a string containing the path to a file or directory, this function will return the trailing name component ex: 'echo basename("etc/sudoers.d") >> sudoers.d'
    $realname = basename($_FILES['file']['name']);
    $hash = md5($realname.strval(random_int(100, 99999)));
    $server_filename = $hash ."_". $realname;
    $uploadfile = $uploaddir . $server_filename;

    /*
     * =================================================================================================
     * '''uptwo'''  >
     * -------------------------------------------------------------------------------------------------
     *              > *parent* (usergroup ?)    >
     *                                          >'''__DIR__''' alias *current_directory* (teacher (?))
     * -------------------------------------------------------------------------------------------------
     *              >
     *              >  uploads                  >
     *                                          >> [hash + '_' + filename]
     *                                          > njflajksbnflaskflafal_esempio.txt
     *                                          > dfflaksfnskjnsdflafff_file2.txt
     * -------------------------------------------------------------------------------------------------
     * =================================================================================================
     */

    //if(!check_file_uploaded_name($realname) || !check_file_uploaded_length($realname)){
    //    die("Security risk error");
    //}

    $tmpname = $_FILES['file']['tmp_name'];
    if (!move_uploaded_file($tmpname, $uploadfile)) {
        # Note: Permission set as: dont write this in production
        #root@vps483509:/var/www/softeng2/public_html# sudo chown -R www-data:www-data uploads/
        #root@vps483509:/var/www/softeng2/public_html# chmod 755 uploads/
        die("Error while moving file");
        //header("location: /error.php?errorID=19"); #TODO: add error
	    //exit();
    }
    //var_dump($_POST);

    $specificClassID = $_POST["classID"];
    $description = $_POST["description"];
    $subjectID = $_POST["topicID"];
    $result = intval($teacher->insert_material($realname, $server_filename, $specificClassID, $description, $subjectID));
    $content = '<div class="alert alert-success" role="alert">
                    Assignment successfully registered. <a href="uploadMaterial.php" class="alert-link">Upload others files</a> or <a href="index.php" class="alert-link">back to your homepage.</a>
                </div>';

}

$page->setContent($content);
$site->render();


/**
* Check $_FILES[][name]
*
* @param (string) $filename - Uploaded file name.
*/
function check_file_uploaded_name ($filename)
{
    (bool) ((preg_match("`^[-0-9A-Z_\.]+$`i",$filename)) ? true : false);
}

/**
* Check $_FILES[][name] length.
*
* @param (string) $filename - Uploaded file name.
*/
function check_file_uploaded_length ($filename)
{
    return (bool) ((mb_strlen($filename,"UTF-8") > 225) ? true : false);
}

?>