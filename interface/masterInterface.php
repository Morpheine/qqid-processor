<?php
/*********************************************
 *  ___              _               _       *
 * | __|_ _  _ _ ___| |_ __  ___ _ _| |_ ___ *
 * | _|| ' \| '_/ _ \ | '  \/ -_) ' \  _(_-< *
 * |___|_||_|_| \___/_|_|_|_\___|_||_\__/__/ *
 *   |_ _|_ _| |_ ___ _ _ / _|__ _ __ ___    *
 *    | || ' \  _/ -_) '_|  _/ _` / _/ -_)   *
 *   |___|_||_\__\___|_| |_| \__,_\__\___|   *
 *********************************************
 *
 * Displays the contents of Bazaar_db's QQID table.
 */
function __autoload($class_name)
{
    include "classes/" . $class_name . '.php';
}

error_reporting(-1);
ini_set('display_errors', 'On');
$type = 'master';
$filters = new Filters($type);
?>
<html>
<?php include("include/head.php"); ?>
<body>
<div class="container-fluid container well well-lg login2" style="padding-bottom: 0px">

    <div class="row">
        <div class="col-md-12">Email
            <a href="../" class="btn btn-info pull-left"><span class="glyphicon glyphicon-chevron-left"></span>Back</a>
            <div class="titleWrapper"><h3 class="formTitle2">QQID Master List</h3></div>
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
                    <th>QQID</th>
                    <th>Password</th>
                    <th>Provisioning Date</th>
                    <th>Expiry Date</th>
                    <th>Course</th>
                    <th>Title</th>
                    <th>Semester</th>
                    <th>Course Start Date</th>
                    <th>Course End Date</th>
                    <th>Enrol Date</th>
                    <th>Mail Sent Date</th>
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