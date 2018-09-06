<?php
/***********************************
 *  ___ _  _   _   ___ ___    __   *
 * | _ \ || | /_\ / __| __|  /  |  *
 * |  _/ __ |/ _ \\__ \ _|   |  |  *
 * |_| |_||_/_/ \_\___/___|  |__|  *
 *  Phase 1: Student Registration  *
 ***********************************/

//Debugging information.
error_reporting(-1);
ini_set('display_errors', 'On');

function __autoload($class_name)
{
    include "classes/" . $class_name . '.php';
}

$marketDb = new Database(3);
$bazaarDb = new Database(2);
$qqidDate = new DateQQID(true);
$studentQry = new MarketQry('enrol');
$studentUpdate = new BazaarQry('students');

//Execute querying of Market for new students.
$rs = $marketDb->qry($studentQry->getEnrolmentQry());

// Calculate the number of results
$num_rows = odbc_num_rows($rs);
echo "<br><b>Number of Results: </b>" . $num_rows . "<br><br>";


if (!$rs) {
    exit("Error in SQL");
}

//While loop for returned rows from the Market db, with SQL statement to insert those values into Bazaar.
$studentQry->buildStudentArray($rs);
$studentArray = $studentQry->getStudentArray();
echo"<pre>";
var_dump($studentArray);
echo"</pre>";

foreach ($studentArray as $studentEntry) {
    //Pass the updateStudents function the values of $studentEntry.
    $studentUpdate->updateStudents($studentEntry);

    //Update the students table.
    $bazaarDb->qry($studentUpdate->getStudentsQry());

    // If the student has withdrawn or exchanged the class, update the withdrawn table
    if($studentEntry['enrolCheck'] == '0'){
        //Pass the updateWithdrawn function the values of $studentEntry.
        $studentUpdate->updateWithdrawn($studentEntry);
        //Update the students table.
        $bazaarDb->qry($studentUpdate->getWithdrawnQry());
    }

}

$marketDb->close();
$bazaarDb->close();
?>