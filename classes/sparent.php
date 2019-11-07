<?php


class sparent extends user
{
    private $name = null;
    private $surname = null;
    private $email = null;
    //private $childs = array();

    public function get_grades_html($childID){

        if(!isset($childID)){
            //todo
        }
        $conn = $this->connectMySql();
        $sql = "SELECT Mark, T.Name, Timestamp, Te.Surname 
                FROM Topic T, MarksRecord M, Teacher Te
                WHERE M.TeacherID = Te.ID
                    AND M.TopicID = T.ID
                    AND M.StudentID = '$childID';"; //todo edit this
        $res = $conn->query($sql);
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


}