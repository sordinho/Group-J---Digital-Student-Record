<?php


class teacher extends user
{
    protected $teacherID = null;
    protected $name = null;
    protected $surname = null;
    protected $email = null;

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->teacherID = $data['teacher_id'];
    }

    private function by_the_end_of_the_week($actual_date,$lecture_date){
        // secondi in una settimana
        $week = 60*60*24*7;
        $max_limit = $lecture_date + $week;
        //cond 1 : non possiamo registrare in anticipo
        if($actual_date < $lecture_date)
            return false;
        //cond 2 : non posso registrare un topic dopo la fine della settimana (dopo la domenica)
        //cond 2.1 : actual_date < lecture_date+ 60*60*24*7
        if($actual_date>= $max_limit)
            return false;
        //cond 2.2 : giorno della settimana di lecture precedente alla domenica della settimana stessa
        //todo : check correctness
        $lecture_day_of_the_week_n = date('N',$lecture_date);
        $actual_day_of_the_week_n = date('N',$actual_date);
        if($actual_day_of_the_week_n<$lecture_day_of_the_week_n)
            return false;
        return true;
    }
    /*
     * @lectureDescritpion --> string with the description of a single lecture
     * @topicID            --> Id of the subject
     * @timestamp          --> date of the lecture "yyyy-mm-dd hh:mm:ss"
     *
     * return               true            if successful
     *                      false           otherwise
     * */
    public function insert_new_lecture_topic($lectureDescription,$topicID,$timestamp){
        //todo : come arriva la data dell'inserzione? UNIX timestamp o già formattata?
        //       come salviamo nel db il timestamp? Al momento sto ipotizzando arrivino
        //       nello stesso formato di actual_date
        $classID = -1;
        // actual unix timestamp
        $actual_date = strtotime(date("Y-m-d H:i:s"));
        // given unix timestamp
        $lecture_date = strtotime($timestamp);
        // secondi in una settimana
        if(!$this->by_the_end_of_the_week($actual_date,$lecture_date))
            return false;
        $conn = $this->connectMySQL();
        $stmt = $conn->prepare("INSERT INTO TopicRecord (TeacherID, Timestamp, Description, TopicID, SpecificClassID) VALUES (?,?,?,?);");
        $stmt->bind_param('issii',$this->teacherID,$timestamp,$lectureDescription,$topicID,$classID);
        $stmt->execute();
        return $stmt->get_result();//True || False
    }

    /*
     * @newDescritpion     --> string with the new description of a single lecture (already registeret)
     * @topicID            --> Id of the subject
     *
     * return               true            if successful
     *                      false           otherwise
     * */

    public function modify_lecture_topic($topicID,$newDescription){
        if(!isset($topicID)||!isset($newDescription)){
            return false;
        }

        $conn = $this->connectMySQL();
        $stmt = $conn->prepare("SELECT TeacherID, Timestamp 
                                      FROM TopicRecord
                                      WHERE TopicID = ?;");
        $stmt->bind_param('i',$topicID);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res->num_rows<=0){
            return false;
        }else{
            $row = $res->fetch_row();
            //controllare se login_iduser == TeacherID

            //modifica entro la fine della settimana
            $actual_date = strtotime(date("Y-m-d H:i:s"));
            $lecture_date = strtotime($row[1]);
            if(!$this->by_the_end_of_the_week($actual_date,$lecture_date))
                return false;

            //controllo se questa lecture l'ha inserita lo stesso
            //prof che sta eseguendo la modifica, se non è lo stesso
            //non permetto la modifica
            if($row[0] != $this->login_iduser){
                return false;
            }
            $res->close();
            $stmt = $conn->prepare("INSERT INTO TopicRecord VALUES (?,?,?,?);");
            $stmt->bind_param("iiss",$this->login_iduser,$topicID,$newDescription,$row[1]);
            $stmt->execute();
            return $stmt->get_result();
        }
    }

    /*
     * Get the topics information for which the teacher is current in charge of
     *
     * return               empty            if successful
     *                      array of array   otherwise
     * */ 
    public function get_topics(){
        $topics = array();
        // TODO create TopicTeacherClass table logic scheme TopicTeacherClass(TopicID, TeacherID, SpecificClassID)
        // Write correct query, use AS to define alias with following names (TopicID, TopicName, TopicDescription)
        $conn = $this->connectMySQL();
        $stmt = $conn->prepare("SELECT  TopicID, TopicName, TopicDescription
                                      FROM TopicTeacherClass JOIN ....
                                      WHERE TeacherID=?;");
        $stmt->bind_param('ii',$topicID);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res->num_rows<=0){
            return false;
        }else{
            $row = $res->fetch_assoc();
            array_push($topics, $row);
        }
        return $topics;
    }

}