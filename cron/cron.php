<?php

error_reporting(0);
//ini_set('display_errors', 'Off');
$q = "";
if (isset($argv[1])) {
    $q = $argv[1];
}
else if (isset($_GET['q'])) {
    $q = $_GET['q'];
} else {
    die("No query\n");
}

// Change the query for each phase, and include that query into the cron file.
//P1
if ($q == "newStudents") {
// create curl resource
    $ch = curl_init();
// set url
    curl_setopt($ch, CURLOPT_URL, "http://applications.learn.utoronto.ca/qqid/cron/query_new_students.php");
    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // $output contains the output string
    $output = curl_exec($ch);
// close curl resource to free up system resources
    curl_close($ch);
    echo $output."\n";
}
//P2
elseif ($q == "provision") {
// create curl resource
    $ch = curl_init();
// set url
    curl_setopt($ch, CURLOPT_URL, "http://applications.learn.utoronto.ca/qqid/cron/provision_qqid.php");
    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // $output contains the output string
    $output = curl_exec($ch);
// close curl resource to free up system resources
    curl_close($ch);
    echo $output."\n";
}

//P3
elseif ($q == "enrol") {
// create curl resource
    $ch = curl_init();
// set url
    curl_setopt($ch, CURLOPT_URL, "http://applications.learn.utoronto.ca/qqid/cron/enrol_students.php");
    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // $output contains the output string
    $output = curl_exec($ch);

// close curl resource to free up system resources
    curl_close($ch);
    echo $output."\n";
}

//P4
elseif ($q == "email") {
// create curl resource
    $ch = curl_init();
// set url
    curl_setopt($ch, CURLOPT_URL, "http://applications.learn.utoronto.ca/qqid/cron/qqid_emailer.php");
    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // $output contains the output string
    $output = curl_exec($ch);

// close curl resource to free up system resources
    curl_close($ch);
    echo $output."\n";
}

//P5
elseif ($q == "course") {
// create curl resource
    $ch = curl_init();
// set url
    curl_setopt($ch, CURLOPT_URL, "http://applications.learn.utoronto.ca/qqid/cron/course_shells.php");
    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // $output contains the output string
    $output = curl_exec($ch);

// close curl resource to free up system resources
    curl_close($ch);
    echo $output."\n";
}

// The following has been deprecated due to the bash script running on the server instead (see push.sh)
/*P6
elseif ($q == "sendPipe") {
// create curl resource
    $ch = curl_init();
// set url
    curl_setopt($ch, CURLOPT_URL, "http://applications.learn.utoronto.ca/qqid/cron/send_pipe.php");
    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // $output contains the output string
    $output = curl_exec($ch);

// close curl resource to free up system resources
    curl_close($ch);
    echo $output."\n";
}

// If the query doesn't exist
else{
    echo "Invalid Query!\n";
}*/
?>