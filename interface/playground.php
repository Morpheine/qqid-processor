<?php
/**
 * Created by PhpStorm.
 * User: brent
 * Date: 2015-05-05
 * Time: 9:11 AM
 */


?>

<html>
<head>
    <title>QQID Enrolment List</title>
    <!--Bootstrap core CSS-->
    <link href="include/css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="include/css/signin.css" rel="stylesheet">
    <link rel="shortcut icon" href="./include/images/favicon.ico?v=2" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="include/js/bootstrap.min.js"></script>
</head>
<body>

<div role="tabpanel">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
        <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
        <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>
    </ul>

    <?php
    $string1 = "FallTest";
    $string2 = "WinterTest";
    $string3 = "SpringTest";
    ?>
    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="Fall"><?php echo $string1; ?></div>
        <div role="tabpanel" class="tab-pane" id="profile"><?php echo $string2; ?></div>
        <div role="tabpanel" class="tab-pane" id="messages"><?php echo $string3; ?></div>
        <div role="tabpanel" class="tab-pane" id="settings">...</div>
    </div>

</div>