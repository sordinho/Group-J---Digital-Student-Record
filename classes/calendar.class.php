<?php

require_once(__DIR__."/../site_config.php");

class calendar
{
    //private static $filename="../files/holidays.csv";
    public static function validate_date($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d and $d->format($format) === $date;
    }

    public static function is_holiday($date, $format = "Y-m-d", $filename = FULL_PATH."files/holidays.csv")
    {
        try {
            $d = new DateTime($date);
            //TD HANDLE EXCEPTIONS
            if($d == false)
                return false;
        }catch (Exception $e){
            return false;
        }
        $toCheck = $d->format($format);
        // Check if Sunday
        $numDay =date("N", strtotime($toCheck));
        if ($numDay == 7 || $numDay == 6) {
            // N: 1 (for Monday) through 7 (for Sunday)
            return true;
        }

        // todo: handle behavior - not handled
        $file = fopen($filename, "r");
        if (!$file) {
            return false;
        }

        while (!feof($file)){
            $line = fgets($file);
            $line = trim($line);
            if($line == $toCheck){
                fclose($file);
                return true;
            }
        }
        fclose($file);
        return false;
    }

    public static function by_the_end_of_the_week($actual_date, $lecture_date)
    {
        // secondi in una settimana
        $week = 60 * 60 * 24 * 7;
        $max_limit = $lecture_date + $week;
        //cond 1 : non possiamo registrare in anticipo
        if ($actual_date < $lecture_date)
            return false;
        //cond 2 : non posso registrare un topic dopo la fine della settimana (dopo la domenica)
        //cond 2.1 : actual_date < lecture_date + 60*60*24*7
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

    // Note: i think is better to use the site_config for such a costant, a change in this should will cause various changes.
    public static function get_hours_per_school_day()
    {
        return 6; // aka hours_per_school_day
    }

    public static function get_days_per_school_week()
    {
        return 5; // aka days_per_school_week
    }

    /**
     * Convert a timestamp into a defined array
     *
     * @param int $nerr
     *
     * @return array[] Returns an array of string that represents a date
     */
    public static function timestamp_to_date($timestamp)
    {
        $date['month'] = date("M", $timestamp);
        $date['day'] = date("d", $timestamp);
        $date['time'] = date("H:i", $timestamp);
        return $date;
    }

    public static function from_num_to_dow($n) {
        $day = "";
        switch ($n) {
            case 0:
                $day = "Monday";
                break;
            case 1:
                $day = "Tuesday";
                break;
            case 2:
                $day = "Wednesday";
                break;
            case 3:
                $day = "Thursday";
                break;
            case 4:
                $day = "Friday";
                break;
        }
        return $day;
    }

    public static function from_dow_to_num($d) {
        $day = -1;
        switch ($d) {
            case "Monday":
                $day = 0;
                break;
            case "Tuesday":
                $day = 1;
                break;
            case "Wednesday":
                $day = 2;
                break;
            case "Thursday":
                $day = 3;
                break;
            case "Friday":
                $day = 4;
                break;
        }
        return $day;
    }

    public static function from_hour_to_slot($h) {
        $hourSlot = -1;
        switch ($h) {
            case "08:00":
                $hourSlot = 0;
                break;
            case "09:00":
                $hourSlot = 1;
                break;
            case "10:00":
                $hourSlot = 2;
                break;
            case "11:00":
                $hourSlot = 3;
                break;
            case "12:00":
                $hourSlot = 4;
                break;
            case "13:00":
                $hourSlot = 5;
                break;
        }

        return $hourSlot;
    }

}