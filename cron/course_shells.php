<?php
function __autoload($class_name)
{
    include "classes/" . $class_name . '.php';
}

$db = new Database(3);
$qqidDate = new DateQQID();
$courseQry = new MarketQry('course');
//Execute querying of Market for new students.
$rs = $db->qry($courseQry->getCoursesQry());

//Create an array for the pipe data.
$pipeData = array();
while ($row = odbc_fetch_array($rs)) {
    $pipeData[] =
        "SCS_" . $row["course_code"] . "_" . $row["section_code"] .
        "|SCS_" . $row["course_code"] . "_" . $row["section_code"] .
        "|Y|" . $row["Title"] . "|N|SCS_0002_007";
}

//get amount of entries
$countEntries = count($pipeData);

$pipeOut = fopen('exports/SCScourses', "w");

$content = "External_Course_Key|Course_Id|Available_Ind|Course_Name|Allow_Guest_Ind|Template_Course_Key";
$content .= "\n";
foreach ($pipeData as $val) {
    $content .= $val . "\n";
}
$content .= '***FileFooter|' . $countEntries . '|' . $qqidDate->getFootDate();

fwrite($pipeOut, $content);
fclose($pipeOut);

?>