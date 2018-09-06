<?php
/****************************************************
 *   ___                         ___         _      *
 *  / __|___ _  _ _ _ ___ ___   / __|___  __| |___  *
 * | (__/ _ \ || | '_(_-</ -_) | (__/ _ \/ _` / -_) *
 * \___\___/\_,_|_| /__/\___|_ \___\___/\__,_\___|  *
 *    |_ _|_ _| |_ ___ _ _ / _|__ _ __ ___          *
 *     | || ' \  _/ -_) '_|  _/ _` / _/ -_)         *
 *    |___|_||_\__\___|_| |_| \__,_\__\___|         *
 ****************************************************
 *
 * Displays the contents of Bazaar_db's students table.
 */
////Debugging information.
//error_reporting(-1);
//ini_set('display_errors', 'On');
////Path to course file
define('COURSE_PATH', '../cron/imports/');
//course filename
$course_file = COURSE_PATH . "courseShellCodes.txt";
$cache_file = './cache/shellCache.csv';

function __autoload($class_name)
{
    include "../cron/classes/" . $class_name . '.php';
}

$db = new Database(3);
$marketQry = new MarketQry('null');
$date = new DateQQID('true');

//Create an array for the course data.
$courseData = array();
//Open the course file.
$courseRead = fopen($course_file, 'r') or die("<br><b>WARNING:</b> Failed to open $course_file to read!<br>");
$cacheRead = fopen($cache_file, 'r') or die("<br><b>WARNING:</b> Failed to open $course_file to read!<br>");

//Grab the current semester.
$currentSemester = substr($date->getSemester(), 0, -5);
$currentYear = substr($date->getSemester(), -2);

//Parse the posted course/section code combination, and pass to the text file.
if (!empty($_POST)) {
    $courses = explode("\n", str_replace("_", "-", str_replace("SCS_", "", trim(strtoupper($_POST['courses'])))));

    $holder = array();
    foreach ($courses as $course) {
        $holder[] = trim($course);
        //var_dump($holder);
    }
    $result = implode("\n", $holder);
    if (!empty($holder)) {
        fclose($courseRead);
        $courseWrite = fopen($course_file, 'w') or die("<br><b>WARNING:</b> Failed to open $course_file to write!<br>");
        fwrite($courseWrite, $result);
        fclose($courseWrite);
        $courseRead = fopen($course_file, 'r') or die("<br><b>WARNING:</b> Failed to open $course_file to read!<br>");
    }

    $updated = true;
}
?>
<html>
<head>
    <title>QQID Course Shell List</title>
    <!--Bootstrap core CSS-->
    <link href="include/css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="include/css/signin.css" rel="stylesheet">
    <link href="include/css/alertify.core.css" rel="stylesheet">
    <link href="include/css/alertify.default.css" rel="stylesheet">
    <link rel="shortcut icon" href="./include/images/favicon.ico?v=2" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="include/js/bootstrap.min.js"></script>
    <script src="include/js/alertify.min.js"></script>
</head>
<body>
<div class="container well well-lg login3" style="margin-bottom: -4px;">
    <a href="../" class="btn btn-info pull-left"><span class="glyphicon glyphicon-chevron-left"></span>Back</a>
    <div class="titleWrapper"><h3 class="formTitle2">Course Shell List</h3></div>
    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#Current" aria-controls="Current" role="tab" data-toggle="tab" class="tabTitle tabTitleC">
                    <?php echo "<img class='legends' src='include/yearImage.php?year=".$currentYear."&semester=".$currentSemester."' draggable='false' ondragstart='return false;'/>";?> Current
                </a>
            </li>
            <li role="presentation">
                <a href="#Fall" aria-controls="Fall" role="tab" data-toggle="tab" class="tabTitle tabTitleF">
                    <img class='legends' src='./include/images/fall.png' draggable="false" ondragstart="return false;"/> Fall
                </a>
            </li>
            <li role="presentation">
                <a href="#Winter" aria-controls="Winter" role="tab" data-toggle="tab" class="tabTitle tabTitleW">
                    <img class='legends' src='./include/images/winter.png' draggable="false" ondragstart="return false;"/> Winter
                </a>
            </li>
            <li role="presentation">
                <a href="#SS" aria-controls="SS" role="tab" data-toggle="tab" class="tabTitle tabTitleS">
                    <img class='legends' src='./include/images/spring.png' draggable="false" ondragstart="return false;"/> Spring / Summer
                </a>
            </li>
            <li role="presentation">
                <a href="#Invalid" aria-controls="Invalid" role="tab" data-toggle="tab" class="tabTitle tabTitleI">
                    <img class='legends' src='./include/images/error.png' draggable="false" ondragstart="return false;"/> Invalid
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <?php
            //Initialize empty string variables for each semester.
            $Winter = "";
            $Fall = "";
            $Spring = "";
            $Invalid = "";

            //Read from courseShellCodes.txt to get all course codes, and sort them numerically.
            while (($courseRow = fgetcsv($courseRead, 0, "-")) !== false) {
                if(array(null) !== $courseRow){
                    $crsCode[] = $courseRow[0] . $courseRow[1];
                }
            }
            sort($crsCode);

            //Read from the cached courses file and store those courses in an array.
            $cachedCourses = array();
            $cachedCodes = array();
            $n = 0;
            while (($cacheRow = fgetcsv($cacheRead, 0, ",")) !== false) {

                $cachedCourses[$n] = array("code"=>$cacheRow[0],"sem"=>$cacheRow[1],"year"=>$cacheRow[2]);
                $cachedCodes[$n] = $cacheRow[0];
                $n++;

            }

            $notCached = array();
            // Courses from interface file
            for($i=0;$i<count($crsCode);++$i) {
                $code = $crsCode[$i];
                // Cached courses in cache.csv
                if (($key = array_search($code, $cachedCodes, true)) !== false && !in_array($code, $notCached)){

                    $cacheValues = $cachedCourses[$key];

                    $cacheCode = $cacheValues["code"];
                    $semester = $cacheValues["sem"];
                    $year = $cacheValues["year"];

                } else {
                    $notCached[] = $code;
                    // If not, query Market for the appropriate information
                    // Execute querying of Market for the course's semester.
                    $marketQry->buildListColourQry($code);
                    $rs = $marketQry->getListColourQry();
                    $semester = substr(implode(odbc_fetch_array($db->qry($rs))), 0, -5);
                    $year = substr(implode(odbc_fetch_array($db->qry($rs))), -2);

                    // Add the new course to the cache file
                    $cacheWrite = fopen($cache_file, 'a');
                    $newCourse = array($code, $semester, $year);
                    fputcsv($cacheWrite, $newCourse);
                }

                //colour code the code based on the semester.
                if($semester == $currentSemester && $year == $currentYear){
                    $Current .= "<img src = 'include/yearImage.php?year=" .$year."&semester=".$semester."' alt='".$year."' class='seasons'/>
                                    SCS_" . substr($code, 0, 4) . "_" . substr($code, 4)."<br/>";
                }elseif($semester == "Winter"){
                    $Winter .= "<img src = 'include/yearImage.php?year=" .$year."&semester=".$semester."' alt='".$year."' class='seasons'/>
                                    SCS_" . substr($code, 0, 4) . "_" . substr($code, 4)."<br/>";
                }elseif($semester == "Fall"){
                    $Fall .= "<img src = 'include/yearImage.php?year=" .$year."&semester=".$semester."' alt='".$year."' class='seasons'/>
                                  SCS_" . substr($code, 0, 4) . "_" . substr($code, 4)."<br/>";
                }elseif($semester == "Spring/Summer"){
                    $Spring .= "<img src = 'include/yearImage.php?year=" .$year."&semester=".$semester."' alt='".$year."' class='seasons'/>
                                    SCS_" . substr($code, 0, 4) . "_" . substr($code, 4)."<br/>";
                }else{
                    $Invalid .= "<img src = 'include/yearImage.php?year=x&semester=none' class='seasons'/>
                                     <span class='nocourse'>SCS_" . substr($code, 0, 4) . "_" . substr($code, 4)."</span><br/>";
                }
            }
            ?>
        </div>
        <!-- Tab panes -->
        <div class="tab-content">

            <div role='tabpanel' class='tab-pane active' id='Current'>
                <div id="content" contenteditable="true" name='courses' class="editdiv form-control">
                    <?php echo $Current; ?>
                </div>
            </div>

            <div role='tabpanel' class='tab-pane' id='Fall'>
                <div id="contentF" contenteditable="true" name='courses' class="editdiv form-control">
                    <?php echo $Fall; ?>
                </div>
            </div>

            <div role='tabpanel' class='tab-pane' id='Winter'>
                <div id="contentW" contenteditable="true" name='courses' class="editdiv form-control">
                    <?php echo $Winter; ?>
                </div>
            </div>

            <div role='tabpanel' class='tab-pane' id='SS'>
                <div id="contentS" contenteditable="true" name='courses' class="editdiv form-control">
                    <?php echo $Spring; ?>
                </div>
            </div>

            <div role='tabpanel' class='tab-pane' id='Invalid'>
                <div id="contentI" contenteditable="true" name='courses' class="editdiv form-control">
                    <?php echo $Invalid; ?>
                </div>
            </div>
        </div>
    </div>

    <form name="target" method="POST" id="target" action="courseShellInterface.php">
        <input id="courses" name="courses" type="hidden" value="">
        <input id="submitBtn" type="submit" class="qqid btn btn-lg btn-primary btn-info submitbtn"/>
        <!--        <button id="submitBtn" type="button" class="qqid btn btn-lg btn-primary btn-info submitbtn">Submit</button>-->
    </form>
    <input id="isUpdated" name="isUpdated" type="hidden" value="<?php echo $updated; ?>"/>

    <p style="margin-bottom: -10px; text-align: justify"><b>Warning:</b> Any alterations to the data above have the
        potential to disrupt the QQID processor's core functions. Do not change without proper knowledge of the
        implications or proper management procedures.
    </p>
</div>

</body>
</html>

<script type="application/javascript">
    // Prevent execution until JQUERY has loaded.
    $("#target").on('submit', function (e) {
//        $("#target").submit(function (e) {
        // Pass the text from the content divs to the variable content
        var content = $("#content").text() + $("#contentF").text() + $("#contentW").text() + $("#contentS").text() + $("#contentI").text();
        // Regex content for correct course/section code combos, assign that to the matches variable
        var matches = content.match(/((?:SCS_)?(?:[0-9]{4}|TEST)(?:[_-]?)\d{3})/gi);
        // Pass the value of matches to the textarea_hidden hidden input. Join on newline
        $("#courses").val(matches.join("\n"));
        //console.log(matches.join("\n"));
        //return false;

        // Provide a pop up alert if there are invalid courses
        var invalidContent = $("#contentI").text();
        var invalidMatch = invalidContent.match(/((?:SCS_)?(?:[0-9]{4}|TEST)(?:[_-]?)\d{3})/gi);
        var invalidStr = invalidMatch.join("<br/>");
        if (invalidMatch.length > 0) {
            // Cancel Submit
            e.preventDefault();
            var alertText = "<b>Warning!</b> The following are invalid course codes: <br/><br/>" + invalidStr + "<br/><br/> Are you sure you'd like to submit?";
            // confirm dialog
            // Change the order of buttons to OK / Cancel
            alertify.set({ buttonReverse: true });
            //Set the focus to be on the Cancel button.
            alertify.set({ buttonFocus: "cancel" });
            alertify.confirm(alertText, function (e) {
                if (e) {
                    // user clicked "ok"
                    $("#target")[0].submit();
                } else {
                    // user clicked "cancel"
                    alertify.error("Aborted Course Shell List update!");
                    e.preventDefault();
                }
            });
        }
    });

    (function($) {
        if($("#isUpdated").val() == true){
            alertify.success("Course Shell List updated!");
        }
    })(jQuery);
</script>