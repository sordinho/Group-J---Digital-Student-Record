<?php

class sparent extends user
{
    private $parent_id = null;
    //private $childs = array();

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->parent_id = $data['parent_id'];
    }

    //returns the result of the query that selects all the grades of @childID
    public function get_grades($childID){

        if(!isset($childID)){
            return array();
        }
        $conn = $this->connectMySql();
        $stmt = $conn->prepare("SELECT t.Name, Mark, Timestamp, u.Surname 
                FROM  Topic t, MarksRecord M, Teacher Te,User u
                WHERE M.TeacherID = Te.ID AND Te.UserID=u.ID AND t.ID=M.TopicID
                    AND M.StudentID = ?");
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
        array_push($childs, $row[0]);
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