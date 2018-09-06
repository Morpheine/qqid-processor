<?php
/****************************************************************
 *   ___   ___  ___ ___    ___     _            __              *
 *  / _ \ / _ \|_ _|   \  |_ _|_ _| |_ ___ _ _ / _|__ _ __ ___  *
 * | (_) | (_) || || |) |  | || ' \  _/ -_) '_|  _/ _` / _/ -_) *
 *  \__\_\\__\_\___|___/  |___|_||_\__\___|_| |_| \__,_\__\___| *
 ****************************************************************
 *
 * This page is to be used as a tentative PHP login for the
 * ProgramAdmin interface. This will be modified with the
 * introduction of Shibboleth.
 */
//Debugging information.
error_reporting(-1);
ini_set('display_errors', 'On');

function __autoload($class_name)
{
    include "interface/classes/" . $class_name . '.php';
}

include './interface/include/control.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>QQID Interface</title>
    <!--Bootstrap core CSS-->
    <link href="./interface/include/css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="./interface/include/css/signin.css" rel="stylesheet">
    <link rel="shortcut icon" href="./interface/include/images/favicon.ico?v=2" />
</head>
<body>
<div class="container well well-lg login">
    <img src="./interface/include/images/scs-logo.png" class="scsLogo"/>
    <h3 class="form-signin-heading" align="center">QQID Database</h3>
    <div style="display: block; margin-right: auto; margin-left: auto;">
        <input type="button" value="Students" class="qqid1 btn btn-lg btn-primary btn-block" onclick="window.location.href='./interface/studentInterface.php'" />
        <input type="button" value="Withdrawals" class="qqid2 btn btn-lg btn-primary btn-block" onclick="window.location.href='./interface/withdrawInterface.php'" />
        <input type="button" value="QQIDs" class="qqid3 btn btn-lg btn-primary btn-block" onclick="window.location.href='./interface/qqidInterface.php'" />
        <input type="button" value="Enrolments" class="qqid4 btn btn-lg btn-primary btn-block" onclick="window.location.href='./interface/enrolInterface.php'" />
        <input type="button" value="Master" class="qqid5 btn btn-lg btn-primary btn-block" onclick="window.location.href='./interface/masterInterface.php'" />
    </div>

    <h3 class="form-signin-heading" align="center">Course Lists</h3>
    <div style="display: block; margin-right: auto; margin-left: auto;">
        <input type="button" value="Enrolment List" class="qqid1 btn btn-lg btn-primary btn-block" onclick="window.location.href='interface/courseEnrolInterface.php'" />
        <input type="button" value="Course Shell List" class="qqid2 btn btn-lg btn-primary btn-block" onclick="window.location.href='interface/courseShellInterface.php'" />
    </div>
    <?php echo "<p align='center'><br/>Logged in as: <b>".$loggedUser->getUserUtorid()."</b></p>"; ?>
</div>
</body>
</html>