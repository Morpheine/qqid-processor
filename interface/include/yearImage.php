<?php
////Debugging information.
error_reporting(-1);
ini_set('display_errors', 'On');

function __autoload($class_name)
{
    include "../classes/" . $class_name . '.php';
}

// Retrieve the year and semester variables from the <img> tags via URL encoding.
$year = $_GET['year'];
$semester = $_GET['semester'];

// Call our Image Generator class & associative functions to generate image.
$imgGen = new ImageGenerator();
$yearImg = $imgGen->buildImg($year, $semester);

?>