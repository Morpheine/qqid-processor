<?php

/**
 * Created by PhpStorm.
 * User: webste52
 * Date: 3/20/2015
 * Time: 4:48 PM
 */
class DateQQID
{

    private $today;
    private $dateTimeOut;
    private $provisionDate;
    private $emailSent;
    private $term;
    private $expiryDate;
    private $semester;
    private $ongoingSem;
    private $footDate;

    /**
     * @param bool $full
     */
    public function __construct($full = false)
    {
        date_default_timezone_set('America/Toronto');
        $this->initialize();
        if($full == true){
            $this->buildTerms();
        }
    }

    private function initialize()
    {
        // Set Today's Date
        $this->setToday(new DateTime());
        //Convert Today's Date to footer style
        $this->setFootDate($this->getToday()->format('H:i:s d/m/Y'));
        //Convert Today's Date to timeout style
        $this->setDateTimeOut($this->getToday()->format('ymd'));
        //Convert Today's Date to provisioning style
        $this->setProvisionDate($this->getToday()->format('Y-m-d'));
        //Convert Today's Date to emailSent style
        $this->setEmailSent($this->getToday()->format('Y-m-d H:i:s'));
    }


    private function buildTerms()
    {
        //current month
        $month = $this->getToday()->format('n');
        //current year (last two digits)
        $year = $this->getToday()->format('y');
        //Set Future Year incrementing today by four months, then format to 4 digit year
        $futureYear = $this->getToday()->add(new DateInterval('P4M'))->format('Y');

        if($month > 8) { // if sept, oct, nov, dec
            $this->setTerm('Fall');
            $this->setExpiryDate($futureYear . '-02-28');
        } else if ($month < 5) { //if jan, feb, mar, apr
            $this->setTerm('Winter');
            $this->setExpiryDate($futureYear . '-09-30');
        } else { //if may, jun, jul, aug
            $this->setTerm('Spring/Summer');
            $this->setExpiryDate($futureYear . '-11-30');
        }

        //Set the $semester value for use in the SQL queries.
        $this->setSemester($this->getTerm() . " - " . $year);
        $this->setOngoingSem($this->getTerm() . " - " . $year);
    }

    /**
     * @return mixed
     */
    public function getDateTimeOut()
    {
        return $this->dateTimeOut;
    }

    /**
     * @param mixed $dateTimeOut
     */
    public function setDateTimeOut($dateTimeOut)
    {
        $this->dateTimeOut = $dateTimeOut;
    }

    /**
     * @return mixed
     */
    public function getEmailSent()
    {
        return $this->emailSent;
    }

    /**
     * @param mixed $emailSent
     */
    public function setEmailSent($emailSent)
    {
        $this->emailSent = $emailSent;
    }

    /**
     * @return mixed
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @param mixed $expiryDate
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;
    }

    /**
     * @return mixed
     */
    public function getFootDate()
    {
        return $this->footDate;
    }

    /**
     * @param mixed $footDate
     */
    public function setFootDate($footDate)
    {
        $this->footDate = $footDate;
    }

    /**
     * @return mixed
     */
    public function getOngoingSem()
    {
        return $this->ongoingSem;
    }

    /**
     * @param mixed $ongoingSem
     */
    public function setOngoingSem($ongoingSem)
    {
        $this->ongoingSem = $ongoingSem;
    }

    /**
     * @return mixed
     */
    public function getProvisionDate()
    {
        return $this->provisionDate;
    }

    /**
     * @param mixed $provisionDate
     */
    public function setProvisionDate($provisionDate)
    {
        $this->provisionDate = $provisionDate;
    }

    /**
     * @return mixed
     */
    public function getSemester()
    {
        return $this->semester;
    }

    /**
     * @param mixed $semester
     */
    public function setSemester($semester)
    {
        $this->semester = $semester;
    }

    /**
     * @return mixed
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * @param mixed $term
     */
    public function setTerm($term)
    {
        $this->term = $term;
    }

    /**
     * @return mixed
     */
    public function getToday()
    {
        return $this->today;
    }

    /**
     * @param mixed $today
     */
    public function setToday($today)
    {
        $this->today = $today;
    }
} 