<?php


class Teacher extends user
{
    private $name = null;
    private $surname = null;
    private $email = null;

    public function insert_new_lecture_topic($lectureDescription,$topicID,$timestamp){
        //todo : come arriva la data dell'inserzione? UNIX timestamp o già formattata?
        //       come salviamo nel db il timestamp? Al momento sto ipotizzando arrivino
        //       nello stesso formato di actual_date

        // actual unix timestamp
        $actual_date = strtotime(date("Y-m-d H:i:s"));
        // given unix timestamp
        $lecture_date = strtotime($timestamp);
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
        $conn = $this->connectMySQL();
        //todo : aggiornare quando TopicRecord sarà nel db
        $stmt = $conn->prepare("INSERT INTO TopicRecord (?,?,?,?);");
        //todo : qual è il tipo delle variabili id? login_iduser
        //       al suo interno salva l'id dell'utente nelle tabelle del db?
        //  NB : uso query preparate per prevenire sql injection
        $stmt->bind_param('ssss',$this->login_iduser,$topicID,$lectureDescription,$timestamp);
        $stmt->execute();
        return $stmt->get_result();//True || False
    }

    public function modify_lecture_topic(){

    }
}