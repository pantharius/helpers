<?php

namespace JDOUnivers\Helpers;

class Month {
    public $days = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];
    private $months = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Décembre'];
    public $month;
    public $year;

    /**
     * Month constructor
     * @param int $month Le mois compris entre 1 et 12
     * @param int $year L'annnée
     * @throws \Exception
     */
    public function __construct(int $month = null, int $year = null){
        if($month === null || $month <1 || $month >12){
            $month = intval(date('m'));
        }
        if($year === null){
            $year = intval(date('Y'));
        }

        $this->month = $month;
        $this->year = $year;
    }

    /**
     * @return \DateTime
     */
    public function getStartingDay(): \DateTime{
        return new \DateTime("{$this->year}-{$this->month}-01");
    }


    /**
     * @return string
     */
    public function toString(): string{

       return $this->months[$this->month -1] . ' ' . $this->year;

    }

    /**
     * @return int
     */
    public function getWeeks(): int {

        $start = $this->getStartingDay();
        $end = (clone $start)->modify('+1 month -1 day');

        $startWeek = intval($start->format('W'));
        $endWeek = intval($end->format('W'));
        if ($endWeek === 1){
            $endWeek = intval((clone $end)->modify('-7 days')->format('W')) +1;
        }
        if ($startWeek  === 53){
            $startWeek = intval((clone $start)->modify('+7 days')->format('W')) -1;
        }
        if ($startWeek  === 52){
            $startWeek = intval((clone $start)->modify('+7 days')->format('W')) -1;
        }
        $weeks = $endWeek - $startWeek + 1;
        if($Weeks < 0){
            $weeks = intval($end->format('W'));
        }
        return $weeks;
     }

     /**
      * @return bool
      */
    public function withinMonth (\DateTime $date): bool{

        return $this->getStartingDay()->format('Y-m') === $date->format('Y-m');

    }

/**
 * @return Month
 */
    public function nextMonth(): Month {
        $month = $this->month +1;
        $year = $this->year;
        if($month > 12) {
            $month = 1;
            $year += 1;
        }
        return new Month($month, $year);
    }

    /**
 * @return Month
 */
    public function previousMonth(): Month {
        $month = $this->month -1;
        $year = $this->year;
        if($month < 1) {
            $month = 12;
            $year -= 1;
        }
        return new Month($month, $year);
    }
}