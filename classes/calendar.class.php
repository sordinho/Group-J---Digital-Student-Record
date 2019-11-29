<?php


class calendar
{

    private $hours_per_school_day = 6;

    public function validate_date($date, $format = 'Y-m-d H:i:s'){
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    public static function by_the_end_of_the_week($actual_date, $lecture_date) {
        // secondi in una settimana
        $week = 60 * 60 * 24 * 7;
        $max_limit = $lecture_date + $week;
        //cond 1 : non possiamo registrare in anticipo
        if ($actual_date < $lecture_date)
            return false;
        //cond 2 : non posso registrare un topic dopo la fine della settimana (dopo la domenica)
        //cond 2.1 : actual_date < lecture_date+ 60*60*24*7
        if ($actual_date >= $max_limit)
            return false;
        //cond 2.2 : giorno della settimana di lecture precedente alla domenica della settimana stessa
        //todo : check correctness
        $lecture_day_of_the_week_n = date('N', $lecture_date);
        $actual_day_of_the_week_n = date('N', $actual_date);
        if ($actual_day_of_the_week_n < $lecture_day_of_the_week_n)
            return false;
        return true;
    }

    public function get_hours_per_school_day() {
        return $this->hours_per_school_day;
    }
}