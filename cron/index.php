<?php
error_reporting(-1);
ini_set('display_errors', 'On');
?>
<!DOCTYPE html>
<html>
<head>
    <title>QQID Back End</title>
    <!--Bootstrap core CSS-->
    <link href="../interface/include/css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="../interface/include/css/signin.css" rel="stylesheet">
    <link rel="shortcut icon" href="../interface/include/images/favicon.ico?v=2" />
</head>
<body>
<div class="container well well-lg login">
    <img src="../interface/include/images/scs-logo.png" class="scsLogo"/>
    <h3 class="form-signin-heading" align="center">QQID Automated Processes</h3>
    <div style="display: block; margin-right: auto; margin-left: auto;">
        <input type="button" value="1) Query New Students" class="qqid1 btn btn-lg btn-primary btn-block" onclick="window.location.href='query_new_students.php'" />
        <input type="button" value="2) Provision QQIDs" class="qqid2 btn btn-lg btn-primary btn-block" onclick="window.location.href='provision_qqid.php'" />
        <input type="button" value="3) Enrol Students" class="qqid3 btn btn-lg btn-primary btn-block" onclick="window.location.href='enrol_students.php'" />
        <input type="button" value="4) Course Shells" class="qqid4 btn btn-lg btn-primary btn-block" onclick="window.location.href='course_shells.php'" />
        <input type="button" value="5) Email Students" class="qqid5 btn btn-lg btn-primary btn-block" onclick="window.location.href='qqid_emailer.php'" />
    </div>

    <h3 class="form-signin-heading" align="center">Imports / Exports</h3>
    <div style="display: block; margin-right: auto; margin-left: auto;">
        <input type="button" value="Imports" class="qqid1 btn btn-lg btn-primary btn-block" onclick="window.location.href='imports/'" />
        <input type="button" value="Exports" class="qqid2 btn btn-lg btn-primary btn-block" onclick="window.location.href='exports/'" />
    </div>

</div>
</body>
</html>