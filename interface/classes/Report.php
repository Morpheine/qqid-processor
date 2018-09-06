<?php

    /**
     * Created by PhpStorm.
     * User: webste52
     * Date: 5/14/14
     * Time: 3:02 PM
     */
    class Report
    {
        private $db;
        private $reportData;
        private $excludes;

        public function __construct($type = NULL)
        {
            $db = new Database();
            $this->setDb($db->getDb());
            if($type) {
                $this->generate($type);
            }
        }

        private function generate($type)
        {
            if($type == 'students') {
                $query = "SELECT  First_name,
                                  Last_Name,
                                  student_num,
                                  preferred_email,
                                  concat('SCS_', course_code, '_', section_code) AS course,
                                  Title,
                                  Semester,
                                  start_date,
                                  end_date,
                                  enrol_date,
                                  enrol_status
                          FROM students";
                $this->buildReport($query);
            }
            if($type == 'withdrawn') {
                $query = "SELECT First_name,
                                 Last_Name,
                                 student_num,
                                 preferred_email,
                                 concat('SCS_', course_code, '_', section_code) AS course,
                                 Title,
                                 Semester,
                                 start_date,
                                 enrol_date,
                                 date_time_updated
                          FROM students_withdrawn";
                $this->buildReport($query);
            }
            if($type == 'qqid') {
                $query = "SELECT * FROM qqid";
                $this->buildReport($query);
            }
            if($type == 'enrolments') {
                $query = "SELECT qqid, course_id, student_num, enrol_date, mail_sent_date FROM enrolments";
                $this->buildReport($query);
            }
            if($type == 'master'){
                $query = "SELECT s.First_Name,
                                 s.Last_Name,
                                 s.student_num,
                                 s.preferred_email,
                                 q.qqid,
                                 q.password,
								 q.provisioning_date,
                                 q.expiry_date,
                                 concat('SCS_', s.course_code, '_', s.section_code) AS course,
                                 s.Title,
                                 s.Semester,
                                 s.start_date,
								 s.end_date,
                                 e.enrol_date,
                                 e.mail_sent_date
                          FROM bazaar_db.students s
                          INNER JOIN bazaar_db.qqid q ON s.student_num = q.student_num
                          INNER JOIN bazaar_db.enrolments e ON s.student_num = e.student_num
                          WHERE s.enrol_status = 'Sale'
                          GROUP BY s.student_num, concat('SCS_', s.course_code, '_', s.section_code)";
                $this->buildReport($query);
            }

        }
        private function buildReport($query){
            if($result = $this->getDb()->query($query)) {
                $holder = array();
                while($row = $result->fetch_array()) {
                    $holder[] = $row;
                }
                $this->setReportData($holder);
            }
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
        public function getReportData()
        {
            return $this->reportData;
        }

        /**
         * @param mixed $reportData
         */
        public function setReportData($reportData)
        {
            $this->reportData = $reportData;
        }

        /**
         * @return mixed
         */
        public function getExcludes()
        {
            return $this->excludes;
        }

        /**
         * @param mixed $excludes
         */
        public function setExcludes($excludes)
        {
            $this->excludes = $excludes;
        }
    }