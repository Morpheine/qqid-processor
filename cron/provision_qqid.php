<?php
/***********************************
 *  ___ _  _   _   ___ ___   ___   *
 * | _ \ || | /_\ / __| __| |_  )  *
 * |  _/ __ |/ _ \\__ \ _|   / /   *
 * |_| |_||_/_/ \_\___/___| /___|  *
 *  Phase 2: Provision QQID's      *
 ***********************************/
//Debugging information.
error_reporting(-1);
ini_set('display_errors', 'On');

function __autoload($class_name)
{
    include "classes/" . $class_name . '.php';
}

$connection = new TransQQid('utorauth');
$bazaarDb = new Database(2);
$qqidDate = new DateQQID(true);
$studentQry = new BazaarQry('provision');
$emailer = new emailBuilder();
$provisionDate = $qqidDate->getDateTimeOut();
$outputTarget = "./exports/qq.20" . $provisionDate . ".csv";
$remoteTarget = "/home/clients/scs/qq.20" . $provisionDate . ".csv";
$blankEmailTarget = "./exports/blank_emails_" . $provisionDate . ".csv";

//$studentQry->buildProvisionQry();
$rs = $bazaarDb->qry($studentQry->getProvisionQry());
$studentQry->buildProvisionArray($rs);

//Create an array to store the resulting data.
$provisionArray = $studentQry->getProvisionArray();
//Export results to a csv file with today's date.
//File contents: First_Name, Last_Name, preferred_email, qqid, password, start_date
$output = fopen($outputTarget, "w");
echo "<br/><pre>";
foreach ($provisionArray as $val) {
    fputcsv($output, $val);
    var_dump($val);
}
//var_dump($provisionArray);
echo "</pre>";
fclose($output);

$num2 = mysqli_num_rows($rs);
echo "Number of students to be provisioned: ".$num2."<br/>";

//Send the .csv file with today's date as the filename to UTORauth.
//Uncomment to send the CSV file.
if ($connection->sendFile($outputTarget, $remoteTarget)) {
    echo "Transfer to UTORAUTH Successful<br/>";
} else {
    var_dump($connection->getError());
}

/**************************************************
 *  _    _           _                    _ _     *
 * | |__| |__ _ _ _ | |__  ___ _ __  __ _(_) |___ *
 * | '_ \ / _` | ' \| / / / -_) '  \/ _` | | (_-< *
 * |_.__/_\__,_|_||_|_\_\ \___|_|_|_\__,_|_|_/__/ *
 **************************************************/
//Build the blank email array
$query = $bazaarDb->qry($studentQry->getBlankEmailQry());
$num = mysqli_num_rows($query);
echo "Number of students with blank emails detected: ".$num."<br/>";

//If there are any students with blank emails, then generate a file & send report.
if($num != 0) {
    $studentQry->buildBEArray($query);

    //Retrieve the Blank Email list as an array
    $rs2 = $studentQry->getBlankEmailArray();
    //Export results to a csv file with today's date.
    $output2 = fopen($blankEmailTarget, "w") or die ("Unable to create file!");
    $list = "<br/>";
    foreach ($rs2 as $val2) {
        fputcsv($output2, $val2);
        $list .= implode(" ", $val2) . "<br/>";
    }
    fclose($output2);
    echo "Generated CSV file at: " . $blankEmailTarget . " <br/><br/>";

    //Build and send an email containing students w/ blank email entries.
    $emailer->buildBlankEmail($list);
    if ($emailer->sendEmail()) {
    } else {
        echo $emailer->getMailError();
    }
}

$bazaarDb->close();
?>