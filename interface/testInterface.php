<?php
/**
 * Created by PhpStorm.
 * User: webste52
 * Date: 5/14/14
 * Time: 3:02 PM
 */

function __autoload($class_name)
{
    include "classes/" . $class_name . '.php';
}

error_reporting(-1);
ini_set('display_errors', 'On');
$type = 'students';
$filters = new Filters($type);
?>
<html>
<?php include("include/head.php"); ?>
<body>
<div class="container-fluid container well well-lg login2" style="padding-bottom: 0px">

    <div class="row">
        <div class="col-md-12">
            <a href="../" class="btn btn-info pull-left"><span class="glyphicon glyphicon-chevron-left"></span>Back</a>
            <h3 class="" align="center" style="">Students</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <table id="report" class="table-bordered table-striped table table-hover" frame="box" rules="all" cellpadding="0" cellspacing="0" border="0">
                <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Student Number</th>
                    <th>Preferred Email</th>
                    <th>Course Code</th>
                    <th>Section Code</th>
                    <th>Section ID</th>
                    <th>Title</th>
                    <th>Semester</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Enrol Date</th>
                    <th>Enrol Status</th>
                    <th>Enrolled</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="row filters">
        <?php foreach ($filters->getFilterList() as $filterIndex => $filterValue) {
            echo "<div id='filters$filterIndex'></div>";
        }?>
    </div>
</div>
</body>
</html>