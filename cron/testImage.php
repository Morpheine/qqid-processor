<?php
////Debugging information.
error_reporting(-1);
ini_set('display_errors', 'On');

function __autoload($class_name)
{
    include "../interface/classes/" . $class_name . '.php';
}

if (!empty($_POST)) {
    // Retrieve the year and semester variables from the <img> tags via URL encoding.
    $year = $_POST['year'];
    $semester = $_POST['semester'];

    // Call our Image Generator class & associative functions to generate image.
    $imgGen = new ImageGenerator();
    $yearImg = $imgGen->buildImg($year, $semester);

    header("Content-type: image/png");
    imagepng($my_img);
}
?>

<html>
<body>
<h3>Enter the last two digits of year (i.e. 14) & select a semester </h3>
<form name='whatever' method='post' style="margin-left: 40px" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <b>Year:</b> 20<input type='text' name='year' size='2'/><br/>
    <b>Semester:</b><br/>
    <input type="radio" name="semester" value="Winter" style="margin-left: 30px">Winter<br/>
    <input type="radio" name="semester" value="Fall" style="margin-left: 30px">Fall<br/>
    <input type="radio" name="semester" value="Spring/Summer" style="margin-left: 30px">Spring/Summer<br/><br/>
    <input type='submit'>
</form>