<?php


class calendar
{

    public static function validate_date($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d and $d->format($format) === $date;
    }

    public static function is_holiday($date, $format = "Y-m-d", $filename = "../files/holidays.csv")
    {
        //todo check correctness
        $d = DateTime::createFromFormat($format, $date);

        // Check if Sunday
        if (date("N", strtotime($d)) == 7) {
            // N: 1 (for Monday) through 7 (for Sunday)
            return true;
        }

        // todo: handle behavior - not handled
        $file = fopen(self::$filename, "r");
        if (!$file) {
            return true;
        }
        while (($line = fgetcsv($file, 1)) !== false) {
            if ($d->format($format) === $date) {
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

}