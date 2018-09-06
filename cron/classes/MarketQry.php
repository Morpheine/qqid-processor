<?php

/**
 * Created by PhpStorm.
 * User: webste52
 * Date: 3/20/2015
 * Time: 5:06 PM
 */
class MarketQry
{
    private $enrolmentQry;
    private $coursesQry;
    private $courseArray;
    private $studentArray;
    private $listColourQry;

    /**
     * @param $type
     */
    public function __construct($type)
    {
        if ($type == 'course') {
            $this->buildCourseList('imports/courseShellCodes.txt');
            $this->buildCourse();
        } elseif ($type == 'enrol') {
            $this->buildCourseList('imports/courseEnrollCodes.txt');
            $this->buildEnrolment();
        } else {
            //Dont worry about it
        }
    }

    /**
     *
     */
    private function buildEnrolment()
    {
        $this->setEnrolmentQry("
        SELECT course.code AS Course_Code
            ,section.code AS Section_Code
            ,section.section_id
            ,section.section_title AS Title
            ,semester.NAME AS Semester
            ,enrolmet.course_section_id
            ,course_start_and_end_date.course_start_date AS start_date
            ,course_start_and_end_date.course_end_date AS end_date
            ,enrolmet.student_id
            ,student.student_number AS student_num
            ,person.first_name1 AS first_name
            ,person.last_name AS last_name
            ,enrolmet.course_section_activity_code AS enrol_status
            ,enrolmet.creation_time AS enrol_date
            ,person_email_info.preferred_email_address
            ,person_email_info.email_pref_status
            ,section.current_status_code secAppStat
            ,course.current_status_code crsAppStat
            ,course.object_status_code as crs_stat
        FROM course
        INNER JOIN section ON course.course_id = section.course_id
        INNER JOIN semester ON section.semester_id = semester.semester_id
        INNER JOIN [v_section_enrollment_stat] AS enrolmet ON section.section_id = enrolmet.course_section_id
        INNER JOIN student ON enrolmet.student_id = student.student_id
        INNER JOIN person ON enrolmet.student_id = person.person_id
        -- Start Email Info
        LEFT JOIN (
            SELECT person_id
                ,email_address AS preferred_email_address
                ,preferred_status AS email_pref_status
            FROM (
                SELECT person_id
                    ,email_address
                    ,preferred_status
                    ,ROW_NUMBER() OVER (
                        PARTITION BY person_id ORDER BY preferred_status DESC
                            ,[release] DESC
                        ) AS rn
                FROM person_email
                ) AS person_preferred_email_info
            WHERE rn = 1
            ) AS person_email_info ON person.person_id = person_email_info.person_id
        -- End Email Info
        -- Start of course_date_start_and_end  Modified 6 March 2013
        LEFT JOIN (
            SELECT section_schedule.section_id
                ,MIN(CASE
                        WHEN section_schedule.b_date_time_tba = '1'
                            THEN 'TBA'
                            --THEN '2100-01-01'
                        ELSE CONVERT(VARCHAR, section_schedule.start_date, 120)
                        END) AS course_start_date_tba
                ,MIN(CASE
                        WHEN section_schedule.b_date_time_tba = '1'
                            --THEN 'TBA'
                            THEN '2100-01-01'
                        ELSE CONVERT(VARCHAR, section_schedule.start_date, 120)
                        END) AS course_start_date
                ,isnull(MAX(CASE
                            WHEN section_schedule.b_date_time_tba = '1'
                                --THEN 'TBA'
                                THEN '1900-01-01'
                            ELSE CONVERT(VARCHAR, section_schedule.end_date, 120)
                            END),
                            --'TBA'
                            '1900-01-01'
                            ) AS course_end_date
            FROM section_schedule
            GROUP BY section_schedule.section_id
            ) AS course_start_and_end_date ON section.section_id = course_start_and_end_date.section_id
        -- End of course_date_start_and_end  Modified 6 March 2013
        WHERE enrolmet.course_section_activity_code <> 'Wait'
            AND (course_start_and_end_date.course_start_date <= CONVERT(DATE, DATEADD(dd, 9, GETDATE()), 102))
            AND (course_start_and_end_date.course_end_date > CONVERT(DATE, GETDATE(), 102))
            AND course.code+section.code IN ('" . implode("','", $this->getCourseArray()) . "')
            --AND course.code + '-' +section.code IN ('1860-249')
            --AND course_start_and_end_date.course_start_date = 'TBA'
        ORDER BY enrol_date
        ");
    }

    /**
     *
     */
    private function buildCourse()
    {
        $this->setCoursesQry("
            SELECT DISTINCT course.code AS course_code,
				section.code AS section_code,
				section.section_title AS Title,
				semester.name AS course_semester,
				course_start_and_end_date.course_date_start AS course_start_d,
				course_start_and_end_date.course_date_end AS course_end_date

			FROM dbo.section LEFT JOIN dbo.course
				ON section.course_id = course.course_id
				LEFT JOIN dbo.semester ON section.semester_id = semester.semester_id
				LEFT JOIN
				(
					SELECT dbo.section_instruction_method.section_id AS section_id,
					dbo.fee.name AS moi,
					dbo.fee.[description] AS [Desc]
				FROM dbo.fee
	 				RIGHT JOIN dbo.instruction_method
					RIGHT JOIN dbo.section_instruction_method
					ON dbo.instruction_method.instruction_method_id = dbo.section_instruction_method.instruction_method_id
					ON dbo.fee.fee_id = dbo.instruction_method.instruction_method_id
				) AS ForMOI	ON ForMOI.section_id = dbo.section.section_id
				LEFT JOIN
				(SELECT dbo.section_schedule.section_id,
					MIN(CASE
							WHEN dbo.section_schedule.b_date_time_tba = '1' THEN 'tba'
							ELSE CONVERT(VARCHAR, dbo.section_schedule.start_date, 120)
							END )   AS course_date_start,
							isnull(MAX(CASE
									WHEN dbo.section_schedule.b_date_time_tba = '1' THEN NULL	-- as Null will be ignored when record have multiple value
							ELSE CONVERT(VARCHAR, dbo.section_schedule.end_date, 120)
					END ),'tba')   AS course_date_end								-- If null returned then then assign TBA
				FROM   dbo.section_schedule
				GROUP  BY dbo.section_schedule.section_id
			) AS course_start_and_end_date ON dbo.section.section_id = course_start_and_end_date.section_id
            WHERE course.code+section.code IN ('".implode("','",$this->getCourseArray())."')
            ORDER BY course.code
        ");
    }

    public function buildListColourQry($course)
    {
        $this->setListColourQry("
            SELECT semester.name AS course_semester
			FROM dbo.section LEFT JOIN dbo.course
				ON section.course_id = course.course_id
				LEFT JOIN dbo.semester ON section.semester_id = semester.semester_id
            WHERE course.code+section.code = '".$course."'");
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

    public function buildStudentArray($rs)
    {
        $holder = array();
        while(odbc_fetch_array($rs)){

        //Check if a student has withdrawn or exchanged the course, and mark it accordingly.
        //Increment the number of dropped/exchanged students by 1 for each Refund/Exchange
            if(odbc_result($rs, "enrol_status") == 'Refund' || odbc_result($rs, "enrol_status") =='Exchange'){
                //Increment the number of dropped entries by 1.
                $enrolCheck = '0';
            }
            else {
                $enrolCheck = '1';
            }
        //Create column variables
            $holder[] = array(
                "firstName"=>iconv('UTF-8', 'CP1252', odbc_result($rs, "first_name")),
                "lastName"=>iconv('UTF-8', 'CP1252', str_replace("'", "", odbc_result($rs, "last_name"))),
                "studentNum"=>odbc_result($rs, "student_num"),
                "prefEmail"=>trim(odbc_result($rs, "preferred_email")),
                "courseCode"=>odbc_result($rs, "Course_Code"),
                "secCode"=>odbc_result($rs, "Section_Code"),
                "secId"=>odbc_result($rs, "section_id"),
                "title"=>iconv('UTF-8', 'CP1252', str_replace("'", "", str_replace("'", "", odbc_result($rs, "Title")))),
                "semester"=>odbc_result($rs, "Semester"),
                "startDate"=>odbc_result($rs, "start_date"),
                "endDate"=>odbc_result($rs, "end_date"),
                "enrolDate"=>odbc_result($rs, "enrol_date"),
                "enrolStatus"=>odbc_result($rs, "enrol_status"),
                "enrolCheck" => $enrolCheck,
                "secAppStat"=>odbc_result($rs, "secAppStat"),
                "crsAppStat"=>odbc_result($rs, "crsAppStat"),
                "crsStatus"=>odbc_result($rs, "crs_stat")
            );
        }
        $this->setStudentArray($holder);
    }



    /**
     * @return mixed
     */
    public function getQqidDate()
    {
        return $this->qqidDate;
    }

    /**
     * @param mixed $qqidDate
     */
    public function setQqidDate($qqidDate)
    {
        $this->qqidDate = $qqidDate;
    }

    /**
     * @return mixed
     */
    public function getEnrolmentQry()
    {
        return $this->enrolmentQry;
    }

    /**
     * @param mixed $enrolmentQry
     */
    public function setEnrolmentQry($enrolmentQry)
    {
        $this->enrolmentQry = $enrolmentQry;
    }

    /**
     * @return mixed
     */
    public function getCoursesQry()
    {
        return $this->coursesQry;
    }

    /**
     * @param mixed $coursesQry
     */
    public function setCoursesQry($coursesQry)
    {
        $this->coursesQry = $coursesQry;
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
    public function getStudentArray()
    {
        return $this->studentArray;
    }

    /**
     * @param mixed $studentArray
     */
    public function setStudentArray($studentArray)
    {
        $this->studentArray = $studentArray;
    }

    /**
     * @return mixed
     */
    public function getListColourQry()
    {
        return $this->listColourQry;
    }

    /**
     * @param mixed $listColourQry
     */
    public function setListColourQry($listColourQry)
    {
        $this->listColourQry = $listColourQry;
    }


} 