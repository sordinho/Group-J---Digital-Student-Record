<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("");
$site->setPage($page);

$officer = new officer();

if (!$officer->is_logged()) {
    header("location: /error.php?errorID=19");
    exit();
}

if (!isset($_POST["description"]) and !isset($_POST["title"])) {

    if (isset($_GET['operation_result'])) {

        $content = "";
        switch ($_GET['operation_result']) {
            case 1:
                $content .= '
								<div class="alert alert-success" role="alert">
									Communication successfully registered. <a href="addCommunication.php" class="alert-link">Add another Communication</a> or <a href="index.php" class="alert-link">back to your homepage.</a>
								</div>
							';
                break;
            case 0:
                $content .= '
								<div class="alert alert-danger" role="alert">
									Error in registering a new communication. <a href="addCommunication.php" class="alert-link">Retry </a> or <a href="index.php" class="alert-link">back to your homepage.</a>
								</div>
							';

                break;
            default:
                $content .= '
								<div class="alert alert-dark" role="alert">
									Operation not allowed.
								</div>
							';
        }

    } else {

        $content = '

                    <div class="card">
                        <h2 style="background-color:rgba(108,108,108,0.9);color:white" class="card-header info-color white-text text-center py-4">
                            Add Communication
                        </h2>
                        
                        <div class="card-body px-lg-5 pt-0 mt-md-5">
                            <div class="form-group">
                                <form method="POST">  
                                
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Title</label>
                                        <input class="form-control" id="title" name="title">
                                    </div>
                                                              
                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    </div>
                                    
                                    <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Send</button>	
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                            ';

    }

} elseif (!isset($_POST['description']) and isset($_POST['title'])) {
    $content = ' 
                    <div class="alert alert-danger" role="alert">
                        Please insert a description. <a href="addCommunication.php" class="alert-link">Retry</a> or <a href="index.php" class="alert-link">back to your homepage.</a>
                    </div>
                    ';
} elseif (!isset($_POST['title']) and isset($_POST['description'])) {
    $content = ' 
                    <div class="alert alert-danger" role="alert">
                        Please insert a title. <a href="addCommunication.php" class="alert-link">Retry</a> or <a href="index.php" class="alert-link">back to your homepage.</a>
                    </div>
                    ';
} else {
    if ($officer->publish_communication($_POST['title'], $_POST['description'])) {
        header("Location: addCommunication.php?operation_result=1");
        die();
    } else {
        header("Location: addCommunication.php?operation_result=0");
        die();
    }
}

$page->setContent($content);
$site->render();