<?php

//non posso rinominarla in parent, parent è "reserved"
class sparent extends user
{
    private $parent_id = null;
    private $name = null;
    private $surname = null;
    private $email = null;
    //private $childs = array();

    public function get_grades($childID){

        if(!isset($childID)){
            //todo
        }
        $conn = $this->connectMySql();
        //TODO: add topic table in db and add the topic to this query
        $stmt = $conn->prepare("SELECT Mark, Timestamp, u.Surname 
                FROM MarksRecord M, Teacher Te,user u
                WHERE M.TeacherID = Te.teacherID AND Te.userID=u.ID
                    AND M.StudentID = ?;");
        $stmt->bind_param('s',$childID);
        $stmt->execute();
        return $stmt->get_result();
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