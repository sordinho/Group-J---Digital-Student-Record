<?php
require_once("../../config.php");

$site = new csite();
initialize_site($site);
$page = new cpage("Teachers' master data");
$site->setPage($page);
$officer = new officer();

$content="";

if(!$officer ->is_logged() ){
    header("location: /error.php?errorID=19");
    exit();
}


if(!empty($_POST)){
    $teacherData=$officer->get_teacher_data();
    $count = 0;
    //$debug = "";
    foreach ($teacherData as $i => $row) {
        if($_POST['status_'.$row['ID']] == "changed"){
            $res = $officer->register_teacher_data($row['ID'],$_POST['name_'.$row['ID']],$_POST['surname_'.$row['ID']],$_POST['email_'.$row['ID']],$_POST['fiscalcode_'.$row['ID']]);
            if(!$res)
                $officer->get_error(28);
            $count++;
            //$debug = $row['ID']."-".$_POST['name_'.$row['ID']]."-".$_POST['surname_'.$row['ID']]."-".$_POST['email_'.$row['ID']]."-".$_POST['fiscalcode_'.$row['ID']];
        }
    }
    if($count>0){
        header("Location: handleTeacherMasterData.php?operation_result=1");
        exit();
    } else {
        $officer->get_error(25);
    }

}else{
    if(isset($_GET['operation_result'])){
        switch ($_GET['operation_result']){
            case 1:
                $content.= <<<OUT
<div class="alert alert-success" role="alert">
  Teachers' master data successfully updated. <a href="handleTeacherMasterData.php" class="alert-link">Update more teachers</a> or <a href="index.php" class="alert-link">back to your homepage.</a>
</div>
OUT;
                break;
            default:
                $content.=<<<OUT
<div class="alert alert-dark" role="alert">
  Operation not allowed.
</div>
OUT;
        }
    } else {
        $content = '<script type="text/javascript"><!--
function modifiedTeacher(elem){
    let id = elem.getAttribute("id").split("_")[1];
    document.getElementById("status_"+id).value="changed";
    let tmp = document.getElementById("status_"+id).value;
    elem.style.backgroundColor = "yellow";
}
--></script>
    
    <div class="card text-center">
                <h2 class="card-header info-color white-text text-center py-4" style="background-color:rgba(108,108,108,0.9);color:white">
                    Teachers\' master data
                </h2>
                <div class="card-body">
                <form method="post" class="form-inline" style="color:#757575" action="handleTeacherMasterData.php">
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">Name</th>
        <th scope="col">Surname</th>
        <th scope="col">Email</th>
        <th scope="col">Fiscal Code</th>
      </tr>
    </thead>
    <tbody>';
        $teacherData = $officer->get_teacher_data();
        foreach ($teacherData as $i => $row) {

            $content .= '<tr>
        <input type="text" id="status_'.$row['ID'].'" value="unchanged" name="status_'.$row['ID'].'" class="form-control" hidden>
        <td>
        <div class="form-group row">
            <div class="col-xs-2 pl-2 pr-2">
            <input type="text" id="name_'. $row['ID'] .'" onchange="modifiedTeacher(this)" value="'.$row['Name'].'" name="name_'. $row['ID'] .'" class="form-control">
            </div>
        </div>        
        </td>
        <td>
        <div class="form-group row">
            <div class="col-xs-2 pl-2 pr-2">
            <input type="text" id="surname_'. $row['ID'] .'" onchange="modifiedTeacher(this)" value="'.$row['Surname'].'" name="surname_'. $row['ID'] .'" class="form-control">
            </div>
        </div>
        </td>
        <td>
        <div class="form-group row">
            <div class="col-xs-2 pl-2 pr-2">
            <input type="text" id="email_'. $row['ID'] .'" onchange="modifiedTeacher(this)" value="'.$row['Email'].'" name="email_'. $row['ID'] .'" class="form-control">
            </div>
        </div>
        </td>
        <td>
        <div class="form-group row">
            <div class="col-xs-2 pl-2 pr-2">
            <input type="text" id="fiscalcode_'. $row['ID'] .'" onchange="modifiedTeacher(this)" value="'.$row['FiscalCode'].'" name="fiscalcode_'. $row['ID'] .'" class="form-control">
            </div>
        </div>
        </td>
      </tr>';
        }

        $content .= '
    </tbody>
  </table>
  <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Submit</button>
  </form>
  </div>
  </div>';
    }
}
$page->setContent($content);
$site->render();
