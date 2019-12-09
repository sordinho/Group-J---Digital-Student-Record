<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Publish new communication");
$site->setPage($page);
$officer = new officer();

if(!$officer ->is_logged()){
    $officer->get_error(19);
    exit();
}

if (!empty($_POST)) {
    if (!isset($_POST['title']) && !isset($_POST['description'])) {
        $content = ' 
                    <div class="alert alert-danger" role="alert">
                        Please insert a title and a description. <a href="publishCommunication.php" class="alert-link">Retry</a> or <a href="index.php" class="alert-link">back to your homepage.</a>
                    </div>
                    ';

    } elseif (!isset($_POST['description'])) {
        $content = ' 
                    <div class="alert alert-danger" role="alert">
                        Please insert a description. <a href="publishCommunication.php" class="alert-link">Retry</a> or <a href="index.php" class="alert-link">back to your homepage.</a>
                    </div>
                    ';
    } elseif (!isset($_POST['title'])) {
        $content = ' 
                    <div class="alert alert-danger" role="alert">
                        Please insert a title. <a href="publishCommunication.php" class="alert-link">Retry</a> or <a href="index.php" class="alert-link">back to your homepage.</a>
                    </div>
                    ';
    } else {
        if ($officer->publish_communication($_POST['title'], $_POST['description'])) {
            header("Location: publishCommunication.php?operation_result=1");
            die();
        } else {
            header("Location: publishCommunication.php?operation_result=0");
            die();
        }
    }
} elseif (!empty($_GET['operation_result'])) {
    switch ($_GET['operation_result']) {
        case 1:
            $content = ' 
								<div class="alert alert-success" role="alert">
								    Communication successfully published. <a href="publishCommunication.php" class="alert-link">Publish another communication</a> or <a href="index.php" class="alert-link">back to your homepage.</a>
								</div>
							';
            break;
        case 0:
            $content = '
								<div class="alert alert-danger" role="alert">
								    Error in publishing a new communication. <a href="publishCommunication.php" class="alert-link">Retry</a> or <a href="index.php" class="alert-link">back to your homepage.</a>
								</div>
							';
            break;
        default:
            $content = '
								<div class="alert alert-dark" role="alert">
								    Operation not allowed.
								</div>
							';
            break;
    }
} else {
    // print form
    $content =<<<OUT
dummy form
<form method="POST">
<label for="exampleFormControlTextarea1">Title</label>
<textarea class="form-control" id="description" name="title" rows="3"></textarea>
<label for="exampleFormControlTextarea1">Description</label>
<textarea class="form-control" id="description" name="description" rows="3"></textarea>
<button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Confirm</button>	
</form>
OUT;
}


$page->setContent($content);
$site->render();