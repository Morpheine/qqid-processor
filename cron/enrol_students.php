<?php
/*************************************
 *  ___ _  _   _   ___ ___    _____  *
 * | _ \ || | /_\ / __| __|  |__  /  *
 * |  _/ __ |/ _ \\__ \ _|    |_  \  *
 * |_| |_||_/_/ \_\___/___|  |____/  *
 *  Phase 3: Blackboard Enrolment    *
 *************************************/
//Debugging information.
error_reporting(-1);
ini_set('display_errors', 'On');
//Correct PHP's detection of line endings for Mac users.
ini_set('auto_detect_line_endings', TRUE);

function __autoload($class_name)
{
    include "classes/" . $class_name . '.php';
}

$connection = new TransQQid('utorauth');
$bazaarDb = new Database(2);
$qqidDate = new DateQQID(true);
$studentQry = new BazaarQry();
$fileDate = $qqidDate->getDateTimeOut();
$provisionDate = $qqidDate->getProvisionDate();
$importTarget = "./imports/qq.20" . $fileDate . ".data.csv";
$remoteTarget = "/home/clients/scs/qq.20" . $fileDate . ".data.csv";
$pipeFile = "./exports/SCS_guest_enroll";

//Receive file
$recvCSV = $connection->recvFile($importTarget, $remoteTarget);

/*********************************************
 *                Parse CSV File             *
 *********************************************/
//Open the .csv file
if(file_exists($importTarget)) {

    $csvFile = fopen($importTarget, 'r') or die("<br><b>WARNING:</b> Failed to open $importTarget !<br>");

    echo "<b><br/>Inserting into qqid table</b>: <br>";

    while (($row = fgetcsv($csvFile)) !== false) {
        //Build variables from the .csv fields
        $firstName = iconv('UTF-8', 'UTF-8//TRANSLIT',str_replace("'", "", $row[0]));
        //$firstName = iconv('UTF-8', 'CP1252',str_replace("'", "", $row[0]));
        //$firstName = $row[0];
        $lastName = iconv('UTF-8', '//TRANSLIT',str_replace("'", "", $row[1]));
        //$lastName = iconv('UTF-8', 'CP1252',str_replace("'", "", $row[1]));
        //$lastName = $row[1];
        $prefEmail = $row[2];
        $qqid = $row[3];
        $password = $row[4];
        $expDate = $row[5];

        //Pass the parsed variables to the query for building
        $studentQry->buildEnrolParseQry($prefEmail, $firstName, $lastName);

        //Execute the query
        $rs2 = $bazaarDb->qry($studentQry->getEnrolParseQry());

        //Assign the captured values of student_num and enrol_date to variables.
        $row2 = mysqli_fetch_assoc($rs2);
        //var_dump($row2);
        $studentNum = $row2["student_num"];
        $enrolDate = $row2["enrol_date"];

        //Insert parsed/queried values into bazaar_db.qqid,
        $studentQry->buildQqidUpdateQuery($qqid, $password, $expDate, $studentNum, $enrolDate, $provisionDate);

        //Execute the bazaar_db.qqid insertion query.
        $bazaarDb->qry($studentQry->getQqidUpdateQry());
        //Echo the values being inserted into the QQID table.
        echo "&bull; " . $qqid . ", ********, " . $expDate . ", " . $studentNum . ", " . $enrolDate . ", " . $provisionDate . "<br>";
    }

    //Close the csvFile.
    fclose($csvFile);
    echo "<br>Processed qqid file data successfully imported to bazaar_db.qqid<br>";
}

/*********************************************
 *              Create Pipe File             *
 *********************************************
 * Created pipe file of qqid student records from today.
 * Send pipe file to Blackboard file server.
 * When this has been done, create an entry in bazaar_db.enrolments
 * leaving the mail_sent_date field blank until the next phase, email phase.
 */

$studentQry->buildPipeFileQry($provisionDate);
$rs3 = $bazaarDb->qry($studentQry->getPipeFileQry());

//Create an array for the pipe data.
$pipeData = array();

//Assign the values of student_num, enrol_date, and preferred_email to variables.
while ($row3 = mysqli_fetch_assoc($rs3)) {
    //Create holder array to store the columns we wish to export to csv.
    //Format: SCS_courseCode_sectionCode|qqid|STUDENT
    $pipeData[] = "SCS_".$row3["course_code"]."_".$row3["section_code"]."|".$row3["qqid"]."|student".PHP_EOL;
}

//get amount of entries
$countEntries = count($pipeData);

//Export results to a pipe file.
//Start by writing a header to the file, then enter all data.
$pipeOut = fopen("$pipeFile","w");
$header = "External_Course_Key|External_Person_Key|Role";
fwrite($pipeOut, $header);
fputs($pipeOut, "\n");

foreach ($pipeData as $val){
    fwrite($pipeOut, $val);
}
echo "<br><b>Generated pipe file at:</b> ".$pipeFile."<br>";

/*********************************************
 *                   _               _       *
 *  ___ _ _  _ _ ___| |_ __  ___ _ _| |_ ___ *
 * / -_) ' \| '_/ _ \ | '  \/ -_) ' \  _(_-< *
 * \___|_||_|_| \___/_|_|_|_\___|_||_\__/__/ *
 *********************************************
 * Get data from the pipe file located at a designated path.
 * Run a while loop that recursively parses the pipe file.
 * Pull information from the pipe file with the expected fields.
 * Query bazaar_db.students to get more information for the
 * .enrolments table.Insert queried/parsed values into the
 * bazaar_db.enrolments table. This is to allow the tracking
 * of which students have been sent to blackboard.
 * Update the mail_sent_date field at a later time.
 */
echo "<br><b>Opening pipe file:</b> ".$pipeFile.".<br>";
//Open the pipe file.
$pipeRead = fopen($pipeFile, 'r') or die("<br><b>WARNING:</b> Failed to open $pipeFile !<br>");

echo "<br><b>Processing pipe file: </b>".$pipeFile.".<br>";

//Get the first line of the file to avoid the header going into the database:
fgetcsv($pipeRead, 0, "|");

while(($pipeRow=fgetcsv($pipeRead, 0, "|")) !== false){
    //Parse the course code from the CSV file, and clean it up to the appropriate format
    $coursePull = str_replace("_", "-", str_replace("SCS_", "", $pipeRow[0]));
    //qqid = qqxxxxxx
    $qqidPull = $pipeRow[1];

    echo "<br> &bullet; <b>Entry found: </b>".$coursePull." | ".$qqidPull."<br>";

    //Query bazaar_db.qqid for student_num, and enrol_date fields
    $studentQry->buildEnrolDateQry($qqidPull);
    $queryQQID = $studentQry->getEnrolDateQry();
//        "SELECT student_num,
//                         enrol_date
//                    FROM bazaar_db.qqid
//                    WHERE qqid = '$qqidPull'";

    //Execute the queryQQID query.
    $rs4 = $bazaarDb->qry($queryQQID);
    //Assign the values of student_num, and enrol_date to variables.
    $row4 = mysqli_fetch_assoc($rs4);
    $studentNum = $row4["student_num"];
    $enrolDate = $row4["enrol_date"];

    //Build & execute the bazaar_db.enrolments insertion query.
    $studentQry->buildEnrolmentsQry($qqidPull, $coursePull, $studentNum, $enrolDate);
    $test = $bazaarDb->qry($studentQry->getEnrolmentsQry());
}
//Generate a footer for the pipe file
//Generate it at this point in the program to avoid entry of the  Footer into the database.
fputs($pipeOut,'***FileFooter|'.$countEntries.'|'.$qqidDate->getFootDate());
//Close the pipe file
fclose($pipeOut);

$bazaarDb->close();
?>