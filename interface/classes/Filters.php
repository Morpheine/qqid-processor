<?php
    /**
     * User: Valhalla
     * Date: 10/27/14
     * Time: 8:30 PM
     */
    class Filters
    {
        private $db;
        private $filters;
        private $filterList;

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
                $this->setFilterList(
                    array(
                        0=>"First Name",
                        1=>"Last Name",
                        2=>"Student Number",
                        3=>"Preferred Email",
                        4=>"Course",
                      //  4=>"Course Code",
                      //  5=>"Section Code",
                       // 6=>"Section ID",
                        5=>"Title",
                        6=>"Semester",
                        7=>"Start Date",
                        8=>"End Date",
                        9=>"Enrol Status",
                       // 13=>"Enrolled"
                    )
                );
                $this->makeFilters();
            }
            if($type == 'withdrawn') {
                $this->setFilterList(

                array(
                        0=>"First Name",
                        1=>"Last Name",
                        2=>"Student Number",
                        3=>"Preferred Email",
                        4=>"Course",
                        5=>"Title",
                        6=>"Semester",
                        7=>"Start Date",
                        8=>"Enrol Date",
                        9=>"Withdrawal Date"
                    )
                );
                $this->makeFilters();
            }
            if($type == 'qqid') {
                $this->setFilterList(
                    array(
                        0=>"QQID",
                        1=>"Password",
                        2=>"Expiry Date",
                        3=>"Student Number",
                        4=>"Enrolment Date",
                        5=>"Provisioning Date"
                    )
                );
                $this->makeFilters();
            }
            if($type == 'enrolments') {
                $this->setFilterList(
                    array(
                        0=>"QQID",
                        1=>"Course Code",
                        2=>"Student Number",
                        3=>"Enrolment Date",
                       // 3=>"Withdrawal Date",
                        4=>"Mail Sent Date"
                    )
                );
                $this->makeFilters();
            }
            if($type == 'master') {
                $this->setFilterList(
                    array(
                    0=>"First Name",
                    1=>"Last Name",
                    2=>"Student Number",
                    3=>"Preferred Email",
                    4=>"QQID",
                    5=>"Password",
                    6=>"Provisioning Date",
                    7=>"Expiry Date",
                    8=>"Course",
                    9=>"Title",
                    10=>"Semester",
                    11=>"Start Date",
                    12=>"End Date",
                    13=>"Enrol Date",
                    14=>"Mail Sent Date"
                    )
                );
                $this->makeFilters();
            }
        }
        private function makeFilters(){
            $filters = array();
            foreach($this->getFilterList() as $filterIndex => $filterValue) {
                $filters[] = array(
                    "column_number" => $filterIndex,
                    "filter_type" => "multi_select",
                    "select_type" => "chosen",
                    "filter_container_id" => "filters" . $filterIndex,
                    "filter_default_label" => $filterValue
                );
            }
            $this->setFilters(json_encode($filters));
        }
        /**
         * @return mixed
         */
        public function getFilterList()
        {
            return $this->filterList;
        }

        /**
         * @param mixed $filterList
         */
        public function setFilterList($filterList)
        {
            $this->filterList = $filterList;
        }

        /**
         * @return mixed
         */
        public function getFilters()
        {
            return $this->filters;
        }

        /**
         * @param mixed $filters
         */
        public function setFilters($filters)
        {
            $this->filters = $filters;
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
    }
?>