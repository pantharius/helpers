<?php

/**
 * Date Helper
 */

namespace JDOUnivers\Helpers;

/**
 * collection of methods for working with dates.
 */
class Date {

    /**
     * get the difference between 2 dates
     *
     * @param  date $from start date
     * @param  date $to   end date
     * @param  string $type the type of difference to return
     * @return string or array, if type is set then a string is returned otherwise an array is returned
     */
    public static function difference($from, $to, $type = null) {
        $d1 = new \DateTime($from);
        $d2 = new \DateTime($to);
        $diff = $d2->diff($d1);
        if ($type == null) {
            //return array
            return $diff;
        } else {
            return $diff->$type;
        }
    }

    /**
     * Business Days
     *
     * Get number of working days between 2 dates
     *
     * Taken from http://mugurel.sumanariu.ro/php-2/php-how-to-calculate-number-of-work-days-between-2-dates/
     *
     * @param  date     $startDate date in the format of Y-m-d
     * @param  date     $endDate date in the format of Y-m-d
     * @param  booleen  $weekendDays returns the number of weekends
     * @return integer  returns the total number of days
     */
    public static function businessDays($startDate, $endDate, $weekendDays = false) {
        $begin = strtotime($startDate);
        $end = strtotime($endDate);

        if ($begin > $end) {
            //startDate is in the future
            return 0;
        } else {
            $numDays = 0;
            $weekends = 0;

            while ($begin <= $end) {
                $numDays++; // no of days in the given interval
                $whatDay = date('N', $begin);

                if ($whatDay > 5) { // 6 and 7 are weekend days
                    $weekends++;
                }
                $begin .= 86400; // +1 day
            };

            if ($weekendDays == true) {
                return $weekends;
            }

            $working_days = $numDays - $weekends;
            return $working_days;
        }
    }

    /**
     * get an array of dates between 2 dates (not including weekends)
     *
     * @param  date    $startDate start date
     * @param  date    $endDate end date
     * @param  integer $nonWork day of week(int) where weekend begins - 5 = fri -> sun, 6 = sat -> sun, 7 = sunday
     * @return array   list of dates between $startDate and $endDate
     */
    public static function businessDates($startDate, $endDate, $nonWork = 6) {
        $begin = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $holiday = array();
        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($begin, $interval, $end);
        foreach ($dateRange as $date) {
            if ($date->format("N") < $nonWork and ! in_array($date->format("Y-m-d"), $holiday)) {
                $dates[] = $date->format("Y-m-d");
            }
        }
        return $dates;
    }

    public static function timeLeftFromNow($date) {
        date_default_timezone_set('Europe/Paris');
        $now = strtotime(date("Y-m-d H:i:s"));
        $date = strtotime($date);
        $time = $now - $date;
        $nbSecondes = $time % 60;
        $nbMinutes = intval($time / 60) % 60;
        $nbHours = intval($time / 3600) % 24;
        $nbDays = intval($time / 86400) % 30;
        $nbMonths = intval($time / 2592000) % 12;
        $nbYears = intval($time / 31104000);

        if ($nbYears > 0)
            return "il y a " . $nbYears . " ans" . (($nbMonths > 0) ? " et " . $nbMonths . " mois" : "");
        else {
            if ($nbMonths > 0)
                return "il y a " . $nbMonths . " mois" . (($nbDays > 0) ? " et " . $nbDays . " jours" : "");
            else {
                if ($nbDays > 0)
                    return "il y a " . $nbDays . " jours" . (($nbHours > 0) ? " et " . $nbHours . " heures" : "");
                else {
                    if ($nbHours > 0)
                        return "il y a " . $nbHours . " heures" . (($nbMinutes > 0) ? " et " . $nbMinutes . " minutes" : "");
                    else {
                        if ($nbMinutes > 0)
                            return "il y a " . $nbMinutes . " minutes" . (($nbSecondes > 0) ? " et " . $nbSecondes . " secondes" : "");
                        else {
                            if ($nbSecondes > 15)
                                return "il y a " . $nbSecondes . " secondes";
                            else
                                return "à l'instant";
                        }
                    }
                }
            }
        }
    }

    public static function durationToUnit($time) {
        $nbSecondes = $time % 60;
        $nbMinutes = intval($time / 60) % 60;
        $nbHours = intval($time / 3600) % 24;
        $nbDays = intval($time / 86400) % 30;
        $nbMonths = intval($time / 2592000) % 12;
        $nbYears = intval($time / 31104000);

        $ret = "";

        $ret .= (($nbYears > 0) ? $nbYears . " ans" : "");

        $ret .= ((($nbYears > 0 && (($nbMonths > 0 && ($nbDays > 0 || $nbHours > 0 || $nbMinutes > 0 || $nbSecondes > 0)) || ($nbDays > 0 && ($nbHours > 0 || $nbMinutes > 0 || $nbSecondes > 0)) || ($nbHours > 0 && ($nbMinutes > 0 || $nbSecondes > 0)) || ($nbMinutes > 0 && $nbSecondes > 0))) ? ", " : (($nbYears > 0 && ($nbMonths > 0 || $nbDays > 0 || $nbHours > 0 || $nbMinutes > 0 || $nbSecondes > 0)) ? " et " : "")));

        $ret .= (($nbMonths > 0) ? $nbMonths . " mois" : "");

        $ret .= (((($nbYears > 0 || $nbMonths > 0) && (($nbDays > 0 && ($nbHours > 0 || $nbMinutes > 0 || $nbSecondes > 0)) || ($nbHours > 0 && ($nbMinutes > 0 || $nbSecondes > 0)) || ($nbMinutes > 0 && $nbSecondes > 0))) ? ", " : ((($nbYears > 0 || $nbMonths > 0) && ($nbDays > 0 || $nbHours > 0 || $nbMinutes > 0 || $nbSecondes > 0)) ? " et " : "")));

        $ret .= (($nbDays > 0) ? $nbDays . " jours" : "");

        $ret .= (((($nbYears > 0 || $nbMonths > 0 || $nbDays > 0) && (($nbHours > 0 && ($nbMinutes > 0 || $nbSecondes > 0)) || ($nbMinutes > 0 && $nbSecondes > 0))) ? ", " : ((($nbYears > 0 || $nbMonths > 0 || $nbDays > 0) && ($nbHours > 0 || $nbMinutes > 0 || $nbSecondes > 0)) ? " et " : "")));

        $ret .= (($nbHours > 0) ? $nbHours . " heures" : "");

        $ret .= (((($nbYears > 0 || $nbMonths > 0 || $nbDays > 0 || $nbHours > 0) && $nbMinutes > 0 && $nbSecondes > 0) ? ", " : ((($nbYears > 0 || $nbMonths > 0 || $nbDays > 0 || $nbHours > 0) && ($nbMinutes > 0 || $nbSecondes > 0)) ? " et " : "")));

        $ret .= (($nbMinutes > 0) ? $nbMinutes . " minutes" : "");

        $ret .= ((($nbYears > 0 || $nbMonths > 0 || $nbDays > 0 || $nbHours > 0 || $nbMinutes > 0) && $nbSecondes > 0) ? " et " : "");

        $ret .= (($nbSecondes > 0) ? $nbSecondes . " secondes" : "");

        return $ret;
    }

    public static function timeFromDateSimplified($date) {
        $diff = self::difference($date, date("Y-m-d"));

        if ($diff->y > 0)
            return $diff->y . " ans";
        if ($diff->m > 0)
            return $diff->m . " mois";
        if ($diff->d > 0)
            return $diff->d . " jours";
        if ($diff->h > 0)
            return $diff->h . " heures";
        if ($diff->i > 0)
            return $diff->i . " minutes";
        if ($diff->s > 0)
            return $diff->s . " secondes";
        return "à l'instant";
    }

    public static function NowToString() {
        $t = microtime(true);
        $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
        $d = new \DateTime(date('Y-m-d H:i:s.' . $micro, $t));

        return $d->format("Y-m-d H:i:s.u"); // note at point on "u"
    }

    public static function NowToStringOnlyDate() {
        $d = new \DateTime("now");
        return $d->format("Y-m-d");
    }

    public static function isHourPastFromNowMoreThan($date, $hours) {
        date_default_timezone_set('Europe/Paris');
        $now = strtotime(date("Y-m-d H:i:s"));
        $date = strtotime($date);
        $time = $now - $date;
        $nbHours = intval($time / 3600) % 24;
        return ($nbHours >= $hours);
    }

    public static function isBeforeNow($date) {
        date_default_timezone_set('Europe/Paris');
        $now = strtotime(date("Y-m-d H:i:s"));
        $date = strtotime($date);
        $time = $now - $date;
        return $time >= 0;
    }

}
