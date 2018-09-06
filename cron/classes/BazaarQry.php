<?php

/**
 * Created by PhpStorm.
 * User: brent
 * Date: 3/20/2015
 * Time: 5:06 PM
 */

class BazaarQry
{
    private $withdrawnQry;
    private $studentsQry;
    private $courseArray;
    private $provisionQry;
    private $provisionArray;
    private $blankEmailQry;
    private $blankEmailArray;
    private $enrolParseQry;
    private $qqidUpdateQry;
    private $pipeFileQry;
    private $pipeFileArray;
    private $enrolmentsQry;
    private $emailInfoQry;
    private $enrolDateQry;
    private $logMailQry;


    /**
     * @param $type
     */
    public function __construct($type = null)
    {
        if ($type == 'enrol') {
            $this->buildCourseList('imports/courseEnrollCodes.txt');
        }
        else if ($type == 'provision') {
            $this->buildCourseList('imports/courseEnrollCodes.txt');
            $this->buildProvisionQry();
            $this->buildBEQry();
        }
    }

    /**
     * @param $studentEntry
     */
    public function updateStudents($studentEntry)
    {
        $this->setStudentsQry("
            REPLACE INTO students (
                    First_Name,
                    Last_Name,
                    student_num,
                    preferred_email,
                    course_code,
                    section_code,
                    section_id,
                    Title,
                    Semester,
                    start_date,
                    end_date,
                    enrol_date,
                    enrol_status,
                    enrol_check,
                    sec_approval,
                    crs_approval,
                    crs_status
                  )
            VALUES(
                '{$studentEntry['firstName']}',
                '{$studentEntry['lastName']}',
                '{$studentEntry['studentNum']}',
                '{$studentEntry['prefEmail']}',
                '{$studentEntry['courseCode']}',
                '{$studentEntry['secCode']}',
                '{$studentEntry['secId']}',
                '{$studentEntry['title']}',
                '{$studentEntry['semester']}',
                '{$studentEntry['startDate']}',
                '{$studentEntry['endDate']}',
                '{$studentEntry['enrolDate']}',
                '{$studentEntry['enrolStatus']}',
                '{$studentEntry['enrolCheck']}',
                '{$studentEntry['secAppStat']}',
                '{$studentEntry['crsAppStat']}',
                '{$studentEntry['crsStatus']}'
            )");
    }


    /**
     * @param $studentEntry
     */
    public function updateWithdrawn($studentEntry)
    {
        $studWithdrawn = new DateQQID(true);
        $this->setWithdrawnQry("
            INSERT IGNORE students_withdrawn (
                First_Name,
                Last_Name,
                student_num,
                preferred_email,
                course_code,
                section_code,
                section_id,
                Title,
                Semester,
                start_date,
                enrol_date,
                enrol_status,
                enrol_check,
                date_time_updated)
            VALUES(
                '{$studentEntry['firstName']}',
                '{$studentEntry['lastName']}',
                '{$studentEntry['studentNum']}',
                '{$studentEntry['prefEmail']}',
                '{$studentEntry['courseCode']}',
                '{$studentEntry['secCode']}',
                '{$studentEntry['secId']}',
                '{$studentEntry['title']}',
                '{$studentEntry['semester']}',
                '{$studentEntry['startDate']}',
                '{$studentEntry['enrolDate']}',
                '{$studentEntry['enrolStatus']}',
                '{$studentEntry['enrolCheck']}',
                '{$studWithdrawn->getEmailSent()}'
            )");
    }

    /**
     *
     */
    public function buildProvisionQry()
    {
        $this->setProvisionQry("
            SELECT s.First_Name,
               s.Last_Name,
               s.student_num,
               s.preferred_email,
               s.Semester,
               s.end_date,
               concat(s.course_code, s.section_code) AS combined
            FROM bazaar_db.students s
            WHERE s.student_num NOT IN (
                SELECT q.student_num FROM bazaar_db.qqid q, bazaar_db.students s
                WHERE q.expiry_date >= s.end_date
                GROUP BY student_num
          ) AND s.enrol_check = '1'
            AND (s.preferred_email NOT LIKE '' OR s.preferred_email IS NOT NULL)
            AND concat(s.course_code, s.section_code) IN ('" . implode("','", $this->getCourseArray()) . "')
            AND s.sec_approval = 'final_approval'
                GROUP BY s.student_num
                ORDER BY s.enrol_date
            ");
        // -- Removed Criteria: AND s.Semester = '{$expiryDate->getSemester()}'
    }

    /**
     * @param $rs
     */
    public function buildProvisionArray($rs)
    {
        $date = new DateQQID(true);
        $holder = array();
        //While loop to fetch all data from the query.
        while ($row = mysqli_fetch_assoc($rs)){
            $holder[] = array(
                "firstName"=>$row["First_Name"],
                "lastName"=>$row["Last_Name"],
                "prefEmail"=>$row["preferred_email"],
                "expiryDate"=>$date->getExpiryDate()
                );
        }
        $this->setProvisionArray($holder);
        return $holder;
    }

    /**
     *
     */
    public function buildBEQry(){
       // $semester = new DateQQID(true);
        $this->setBlankEmailQry("
            SELECT student_num,
                First_Name,
                Last_Name
            FROM students
             WHERE student_num NOT IN (
            SELECT student_num FROM bazaar_db.qqid
          )  AND enrol_check = '1'
             AND (preferred_email LIKE '' OR preferred_email IS NULL)
                GROUP BY student_num
                ORDER BY student_num
        ");
    }

    /**
     * @param $rs2
     */
    public function buildBEArray($rs2){
        $holder = array();

        while ($row2 = mysqli_fetch_assoc($rs2)) {
            $holder[] = array (
                "firstName"=> $row2["First_Name"],
                //"firstName"=> iconv('UTF-8', 'CP1252',$row2["First_Name"]),
                "lastName"=> $row2["Last_Name"],
                //"lastName"=> iconv('UTF-8', 'CP1252',$row2["Last_Name"]),
                "studentNum"=> $row2["student_num"]
            );
        }
        $this->setBlankEmailArray($holder);
    }

    /**
     * @param $prefEmail
     * @param $firstName
     * @param $lastName
     */
    public function buildEnrolParseQry($prefEmail, $firstName, $lastName){
        //Query bazaar_db.students for student_num, and enrol_date fields
        //Where a student shares the same first name, last name, and email as our processed .csv file.
        $this->setEnrolParseQry("
                    SELECT student_num,
                            enrol_date,
                            preferred_email
                    FROM bazaar_db.students
                    WHERE preferred_email = '$prefEmail'
                    -- AND First_Name = '$firstName'
                    -- AND Last_Name = '$lastName'
        ");
        var_dump($this->getEnrolParseQry());
    }

    /**
     * @param $qqid
     * @param $password
     * @param $expDate
     * @param $studentNum
     * @param $enrolDate
     * @param $provisionDate
     */
    public function buildQqidUpdateQuery($qqid, $password, $expDate, $studentNum, $enrolDate, $provisionDate){
        $this->setQqidUpdateQry("
                INSERT IGNORE bazaar_db.qqid (
                          qqid,
                          password,
                          expiry_date,
                          student_num,
                          enrol_date,
                          provisioning_date
                        )
                VALUES ('$qqid',
                        '$password',
                        '$expDate',
                        '$studentNum',
                        '$enrolDate',
                        '$provisionDate')
        ");
        var_dump($this->getQqidUpdateQry());
    }

    /**
     * @param $provisionDate
     */
    public function buildPipeFileQry($provisionDate){
      $this->setPipeFileQry("
            SELECT s.course_code AS course_code,
                    s.section_code AS section_code,
                    q.qqid AS qqid
            FROM bazaar_db.students s, bazaar_db.qqid q
            WHERE s.student_num = q.student_num
            AND q.expiry_date > '$provisionDate'
            AND s.enrol_check = '1'
        ");
        var_dump($this->getPipeFileQry());
    }

    /**
     * @param $qqid
     * @param $course
     * @param $studentNum
     * @param $enrolDate
     */
    public function buildEnrolmentsQry($qqid, $course, $studentNum, $enrolDate){
        $this->setEnrolmentsQry("
            INSERT IGNORE bazaar_db.enrolments (
                      qqid,
                      course_id,
                      student_num,
                      enrol_date,
                      withdrawal_date,
                      mail_sent_date
                    )
            VALUES ('$qqid','$course','$studentNum','$enrolDate','','')
        ");
    }

    /**
     * @param $today
     */
    public function buildEmailInfo($today){
        $this->setEmailInfoQry("
                SELECT
                    s.course_code AS course_code,
                    s.section_code AS section_code,
                    s.start_date AS start_date,
                    s.First_Name AS fname,
                    s.Last_Name AS lname,
                    s.student_num AS student_num,
                    s.preferred_email AS email,
                    s.Title AS title,
                    s.Semester AS semester,
                    q.qqid AS qqid,
                    q.password AS password,
                    q.expiry_date,
                    e.course_id,
                    e.mail_sent_date
                FROM
                    bazaar_db.students s
                    JOIN bazaar_db.enrolments e ON s.student_num = e.student_num AND concat(s.course_code,'-',section_code) = e.course_id
                    JOIN bazaar_db.qqid q ON s.student_num = q.student_num
                WHERE
                            e.mail_sent_date = '0000-00-00 00:00:00'
                AND			q.expiry_date > '".$today."'
        ");
    }

    /**
     * @param $qqid
     */
    public function buildEnrolDateQry($qqid){
        $this->setEnrolDateQry("
            SELECT student_num,
                 enrol_date
            FROM bazaar_db.qqid
            WHERE qqid = '$qqid'
        ");
    }

    /**
     * @param $mailDate
     */
    public function buildLogMailQry($mailDate){
        $this->setLogMailQry("
                  UPDATE bazaar_db.enrolments
                  SET mail_sent_date='$mailDate'
                  WHERE enrolments.mail_sent_date = '0000-00-00 00:00:00'
                ");
    }

    /**
     * @param $course_file
     */
    private function buildCourseList($course_file)
    {
        $courseRead = fopen($course_file, 'r') or die("<br><b>WARNING:</b> Failed to open $course_file !<br>");

        $combined = array();
        echo "<br><b>Processing course file: </b>" . $course_file . ".<br>";
        while (($courseRow = fgetcsv($courseRead, 0, "-")) !== false) {
            $combined[] = $courseRow[0] . $courseRow[1];
        }
        $this->setCourseArray($combined);
    }

    /**
     * @return mixed
     */
    public function getStudentsQry()
    {
        return $this->studentsQry;
    }

    /**
     * @param mixed $studentsQry
     */
    public function setStudentsQry($studentsQry)
    {
        $this->studentsQry = $studentsQry;
    }

    /**
     * @return mixed
     */
    public function getWithdrawnQry()
    {
        return $this->withdrawnQry;
    }

    /**
     * @param mixed $withdrawnQry
     */
    public function setWithdrawnQry($withdrawnQry)
    {
        $this->withdrawnQry = $withdrawnQry;
    }

    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param mixed $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return mixed
     */
    public function getCourseArray()
    {
        return $this->courseArray;
    }

    /**
     * @param mixed $courseArray
     */
    public function setCourseArray($courseArray)
    {
        $this->courseArray = $courseArray;
    }

    /**
     * @return mixed
     */
    public function getProvisionQry()
    {
        return $this->provisionQry;
    }

    /**
     * @param mixed $provisionQry
     */
    public function setProvisionQry($provisionQry)
    {
        $this->provisionQry = $provisionQry;
    }

    /**
     * @return mixed
     */
    public function getProvisionArray()
    {
        return $this->provisionArray;
    }

    /**
     * @param mixed $provisionArray
     */
    public function setProvisionArray($provisionArray)
    {
        $this->provisionArray = $provisionArray;
    }

    /**
     * @return mixed
     */
    public function getBlankEmailQry()
    {
        return $this->blankEmailQry;
    }

    /**
     * @param mixed $blankEmailQry
     */
    public function setBlankEmailQry($blankEmailQry)
    {
        $this->blankEmailQry = $blankEmailQry;
    }

    /**
     * @return mixed
     */
    public function getBlankEmailArray()
    {
        return $this->blankEmailArray;
    }

    /**
     * @param mixed $blankEmailArray
     */
    public function setBlankEmailArray($blankEmailArray)
    {
        $this->blankEmailArray = $blankEmailArray;
    }

    /**
     * @return mixed
     */
    public function getEnrolParseQry()
    {
        return $this->enrolParseQry;
    }

    /**
     * @param mixed $enrolParseQry
     */
    public function setEnrolParseQry($enrolParseQry)
    {
        $this->enrolParseQry = $enrolParseQry;
    }

    /**
     * @return mixed
     */
    public function getQqidUpdateQry()
    {
        return $this->qqidUpdateQry;
    }

    /**
     * @param mixed $qqidUpdateQry
     */
    public function setQqidUpdateQry($qqidUpdateQry)
    {
        $this->qqidUpdateQry = $qqidUpdateQry;
    }
    /**
     * @return mixed
     */
    public function getPipeFileQry()
    {
        return $this->pipeFileQry;
    }

    /**
     * @param mixed $pipeFileQry
     */
    public function setPipeFileQry($pipeFileQry)
    {
        $this->pipeFileQry = $pipeFileQry;
    }

    /**
     * @return mixed
     */
    public function getPipeFileArray()
    {
        return $this->pipeFileArray;
    }

    /**
     * @param mixed $pipeFileArray
     */
    public function setPipeFileArray($pipeFileArray)
    {
        $this->pipeFileArray = $pipeFileArray;
    }

    /**
     * @return mixed
     */
    public function getEnrolmentsQry()
    {
        return $this->enrolmentsQry;
    }

    /**
     * @param mixed $enrolmentsQry
     */
    public function setEnrolmentsQry($enrolmentsQry)
    {
        $this->enrolmentsQry = $enrolmentsQry;
    }

    /**
     * @return mixed
     */
    public function getEmailInfoQry()
    {
        return $this->emailInfoQry;
    }

    /**
     * @param mixed $emailInfoQry
     */
    public function setEmailInfoQry($emailInfoQry)
    {
        $this->emailInfoQry = $emailInfoQry;
    }

    /**
     * @return mixed
     */
    public function getEnrolDateQry()
    {
        return $this->enrolDateQry;
    }

    /**
     * @param mixed $enrolDateQry
     */
    public function setEnrolDateQry($enrolDateQry)
    {
        $this->enrolDateQry = $enrolDateQry;
    }

    /**
     * @return mixed
     */
    public function getLogMailQry()
    {
        return $this->logMailQry;
    }

    /**
     * @param mixed $logMailQry
     */
    public function setLogMailQry($logMailQry)
    {
        $this->logMailQry = $logMailQry;
    }


}
