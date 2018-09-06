<?php
/***********************************
 *  ___ _  _   _   ___ ___   _ _   *
 * | _ \ || | /_\ / __| __| | | |  *
 * |  _/ __ |/ _ \\__ \ _|  |_  _| *
 * |_| |_||_/_/ \_\___/___|   |_|  *
 *                                 *
 *  Phase 4: Email Access Letters  *
 ***********************************
 * Query Bazaar for student information. Filter by those with non-expired QQIDs
 * who have not received an email.
 */

//Debugging information.
error_reporting(-1);
ini_set('display_errors', 'On');

function __autoload($class_name)
{
    include "./classes/" . $class_name . '.php';
}
$db = new Database(2);
$date = new DateQQID(true);
$bazaarQry = new BazaarQry('enrol');
$emailer = new emailBuilder();
$today = $date->getProvisionDate();


//Gather all relevant information for the email.
$bazaarQry->buildEmailInfo($today);
$rs = $db->qry($bazaarQry->getEmailInfoQry());

//Send emails to all students who have not received an updated email.
while ($row = mysqli_fetch_assoc($rs)) {
    $emailer->buildQqidEmail($row);
    $bazaarQry->buildLogMailQry($date->getEmailSent());

    if($emailer->sendEmail()){
        $rs2 = $db->qry($bazaarQry->getLogMailQry());
    }
    else{
        echo $emailer->getMailError();
    }
}

//Close the connections to all databases.
$db->getDb()->close();
?>