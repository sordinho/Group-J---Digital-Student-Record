<?php


class sparent extends user
{
    private $parent_id = null;
    private $name = null;
    private $surname = null;
    private $email = null;
    //private $childs = array();

    public function get_grades_html($childID){

        if(!isset($childID)){
            //todo
        }
        $conn = $this->connectMySql();
        $stmt = $conn->prepare("SELECT Mark, T.Name, Timestamp, Te.Surname 
                FROM Topic T, MarksRecord M, Teacher Te
                WHERE M.TeacherID = Te.ID
                    AND M.TopicID = T.ID
                    AND M.StudentID = ?;");
        $stmt->bind_param('s',$childID);
        $stmt->execute();
        $res = $stmt->get_result();
        $content ="";
        if($res->num_rows > 0){
            $content = '
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Grade</th>
      <th scope="col">Subject</th>
      <th scope="col">Professor</th>
      <th scope="col">Category</th>
    </tr>
  </thead>
  <tbody>';

            while($row = $res->fetch_row()){
                //todo decidere se nella colonna professor stamperemo solo il cognome o anche il nome
                //  inoltre cosa intendiamo con category? Nelle tabelle dello schema AR non c'è il campo
                $content.="
    <tr>
      <th scope='row'>$row[2]</th>
      <td>$row[0]</td>
      <td>$row[1]</td>
      <td>$row[3]</td>
      <td>??</td>
    </tr>";
            }
            $content.='</tbody></table>';
        }
        return $content;
    }

    // Register the childs in a session
    public function retrieve_and_register_childs(){

      $childs = array();
      $conn = $this->connectMySql();
      $stmt = $conn->prepare("SELECT StudentID
              FROM Parent
              WHERE ID = ?;");
      $stmt->bind_param('d',$this->parent_id);
      $stmt->execute();
      $res = $stmt->get_result();
      while($row = $res->fetch_row()){
        array_push($childs, row[0]);
      }
      $_SESSION['childsID'] = $childs;
      //$_SESSION['childNames'] = $child_names;
    }
    // Register a child as the current to view and analyze by saving the studentID into the session
    public function set_current_child($childID){
      $_SESSION['curChild'] = $childID;
      //$_SESSION['childNames'] = $child_names;

    }
    // Return -1 if no current child was choosen until now, return the studentID of the child otherwise
    public function get_current_child($childID){
        return isset($_SESSION['curChild']) ? $_SESSION['curChild'] : -1; 
    }

}